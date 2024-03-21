<?php
namespace App\Http\Controllers;

Use DB;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineVariant;
use App\Models\User;
use App\Models\Invoice;

use App\Http\Api\InvoiceRenderer;



class CheckoutController extends PublicController
{
  public function show($action) 
  {
    switch ($action) {
			case 'address':
				$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();
				$order->status = 'checkout-address';
				$order->save();

				$user = User::find(auth()->user()->id);

				if ($user->stripe_id == null) {
					$options = [
						'name' => $user->firstName . ' ' . $user->lastName,
						'email' => $user->email,
						'metadata' => [
							'id' => $user->id,
						],
					];
			
					$stripeUser = $user->createAsStripeCustomer($options);
				}

				$addresses = DB::select('SELECT
					a.id,
					a.userId,
					a.defaultBilling,
					a.firstName,
					a.lastName,
					a.company,
					a.line1,
					a.line2,
					a.line3,
					a.city,
					a.region,
					co.name AS country,
					co.code AS countryCode,
					a.postCode,
					a.phone,
					a.email
					FROM addresses AS a
					INNER JOIN countries AS co ON co.code=a.country
					WHERE a.userId = ?
					ORDER BY a.defaultBilling DESC, a.firstName ASC, a.lastName ASC',
					[auth()->user()->id]
				);

				$countries = DB::select('SELECT * FROM countries ORDER BY name ASC');

				return view('public/checkout', compact(
					'action',
					'addresses',
					'countries',
				));
				break;

			case 'summary':
				$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();
				$order->status = 'checkout-summary';
				$order->save();

				$checkout = DB::select('SELECT
					o.*
					FROM orders AS o
					WHERE o.userId = ?
					AND o.type = "basket"
					LIMIT 1',
					[auth()->user()->id]
				);

				if (empty($checkout)) {
					return redirect('/basket');

				} else {
					$checkout = $checkout[0];

					$checkout->billingAddress = DB::select('SELECT
						a.*
						FROM addresses AS a
						WHERE a.userId = ?
						AND a.defaultBilling = true',
						[auth()->user()->id]
					);

					$checkout->deliveryAddress = DB::select('SELECT
						a.*
						FROM addresses AS a
						WHERE a.id = ?',
						[$checkout->deliveryAddressId]
					);
					
					if (empty($checkout->deliveryAddressId) || empty($checkout->billingAddress)) {
						return redirect('/checkout/address')->withErrors(['1' => 'Please select an address.']);
					}

					$checkout->deliveryAddress = $checkout->deliveryAddress[0];
					$checkout->billingAddress = $checkout->billingAddress[0];

					$checkout->lines = DB::select('SELECT
						ol.*,
						p.id AS productId,
						p.title,
						p.subtitle,
						ol.price,
						IF(isnull(ol.assetId), a.fileName, a2.fileName) AS fileName
						FROM order_lines AS ol
						INNER JOIN products AS p ON p.id = ol.productId
						LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
						LEFT JOIN asset AS a ON a.id = pi.assetId
						LEFT JOIN asset AS a2 ON a2.id = ol.assetId
						WHERE ol.orderId = ?
						GROUP BY ol.id
						ORDER BY ol.created_at ASC',
						[$checkout->id]
					);

					$checkout->lines = cacheImages($checkout->lines, 600, 600, true, 'EFEFEF');

					foreach ($checkout->lines as $i => $line) {
						$checkout->lines[$i]->variants = DB::select('SELECT
							olv.*,
							pv.id AS variantId,
							pv.title,
							pv.type,
							a.fileName,
							pv.colour,
							pv2.id AS parentVariantId,
							pv2.title AS parentTitle
							FROM order_line_variants AS olv
							INNER JOIN product_variants AS pv ON pv.id = olv.variantId
							INNER JOIN product_variants AS pv2 ON pv2.id = pv.parentVariantId
							LEFT JOIN asset AS a ON a.id = pv.assetId
							WHERE olv.orderLineId = ?
							GROUP BY olv.id',
							[$line->id]
						);
					}
				}

				return view('public/checkout', compact(
					'action',
					'checkout',
				));
				break;
			
			case 'payment':
				$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();
				$order->status = 'checkout-payment';
				$order->save();

				$checkout = Order::where('userId', auth()->user()->id)->first();

				if ($checkout->deliveryAddressId == null || !$billingAddress = Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->first()) {
					return redirect('/checkout/address')->withErrors(['1' => 'Please select an address.']);
				}

				// $paymentMethods = [];

				// foreach (auth()->user()->paymentMethods() as $i => $method) {
				// 	$paymentMethods[$i] = [
				// 		'id' => $method->id,
				// 		'brand' => ucfirst($method->card->brand),
				// 		'last4' => $method->card->last4,
				// 		'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
				// 		'postcode' => $method->billing_details->address->postal_code,
				// 	];
				// };

				// $billingAddress = DB::select('SELECT
				// 	a.*
				// 	FROM addresses AS a
				// 	INNER JOIN checkout AS c ON c.billingAddressId = a.id
				// 	WHERE c.userId = ?
				// 	LIMIT 1', 
				// 	[auth()->user()->id]
				// );

				// $billingAddress = $billingAddress[0];

				// $payment = auth()->user()->pay(
				// 	$checkout->total * 100
				// );
				// $clientSecret = $payment->client_secret;

				$total = $checkout->total;

				return view('public/checkout', compact(
					'action',
					'billingAddress',
					// 'paymentMethods',
					// 'clientSecret',
					'total',
				));
				break;
				
			default:
				return redirect('/checkout/address');
				break;
		}
  }


	// ADDRESSES --------------------------------------------------
	public function continueAddress($deliveryId)
	{
		Order::where('userId', auth()->user()->id)->where('type', 'basket')->update([
			'deliveryAddressId' => $deliveryId,
			'status' => 'summary'
		]);

		return redirect('/checkout/summary');
	}

	public function addAddress($addressData)
	{
		$addressData = json_decode($addressData, true);

		$address = [];

		foreach ($addressData as $i => $line) {
			$address[$line[0]] = $line[1];
		}

		$defaultBilling = isset($address['defaultbilling']) && $address['defaultbilling'] == 'true' ? 1 : 0;

		if ($defaultBilling) {
			Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->update(['defaultBilling' => 0]);
		}

		$company = !empty($address['company']) ? $address['company'] : null;
		$line2 = !empty($address['line2']) ? $address['line2'] : null;
		$line3 = !empty($address['line3']) ? $address['line3'] : null;
		$region = !empty($address['region']) ? $address['region'] : null;

		$request = [
			'userId' => auth()->user()->id,
			'defaultBilling' => $defaultBilling,
			'firstName' => $address['firstname'],
			'lastName' => $address['lastname'],
			'company' => $company,
			'line1' => $address['line1'],
			'line2' => $line2,
			'line3' => $line3,
			'city' => $address['city'],
			'region' => $region,
			'country' => $address['country'],
			'postCode' => $address['postcode'],
			'phone' => $address['phone'],
			'email' => $address['email'],
		];

		if (!empty($address['update']) && Address::exists($address['update'])) {
			Address::where('id', $address['update'])->update($request);
			$resultId = $address['update'];
			$updated = true;

		} else {
			$resultId = Address::create($request);
			$resultId = $resultId->id;
			$updated = false;
		}

		$result = DB::select('SELECT
			a.id,
			a.userId,
			a.defaultBilling,
			a.firstName,
			a.lastName,
			a.company,
			a.line1,
			a.line2,
			a.line3,
			a.city,
			a.region,
			co.name AS country,
			co.code AS countryCode,
			a.postCode,
			a.phone,
			a.email
			FROM addresses AS a
			INNER JOIN countries AS co ON co.code=a.country
			WHERE a.id = ?
			ORDER BY a.defaultBilling DESC, a.firstName ASC, a.lastName ASC
			LIMIT 1',
			[$resultId]
		);

		$result = $result[0];

		if ($defaultBilling) {
			$options = [
				'address' => [
					'city' => $result->city,
					'country' => $result->country,
					'line1' => $result->line1,
					'line2' => $result->line2,
					'postal_code' => $result->postCode,
					'state' => $result->region,
				],
			];

			auth()->user()->updateStripeCustomer($options);
		}

		return [
			'updated' => $updated, 
			'data' => $result
		];
	}

	public function setBillingAddress(int $id): bool
	{
		Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->update(['defaultBilling' => 0]);

		if ($default = Address::find($id)) {
			$default->update(['defaultBilling' => 1]);

			$options = [
				'address' => [
					'city' => $default->city,
					'country' => $default->country,
					'line1' => $default->line1,
					'line2' => $default->line2,
					'postal_code' => $default->postCode,
					'state' => $default->region,
				],
			];

			auth()->user()->updateStripeCustomer($options);

			return true;
		}

		return false;
	}

	public function deleteAddress(int $id): bool
	{
		if ($address = Address::find($id)) {
			$address->delete();

			return true;
		}

		return false;
	}	


	// PAYMENT --------------------------------------------------
	public function continuePayment($paymentMethod)
	{
		Order::where('userId', auth()->user()->id)->update([
			'paymentMethodId' => $paymentMethod,
			'status' => 'summary',
		]);

		return redirect('/checkout/summary');
	}

	public function addPaymentMethod($id) {
		$result = auth()->user()->addPaymentMethod($id);

		return $result;
	}

	public function deletePaymentMethod($id) {
		$result = auth()->user()->deletePaymentMethod($id);

		return $result;
	}


	// SUMMARY --------------------------------------------------
	public function continueSummary(int $userId = 0)
	{
		$orderId = Order::createOrder($userId);

		if ($orderId == 0) {
			return redirect('/checkout/summary')->withErrors(['1' => 'Something went wrong. Please review your order and try again.']);
		}

		Invoice::createInvoice($orderId);

		return redirect('/order-successful/' . $orderId);
	}

	public function orderSuccessful($orderId)
	{
		$order = DB::select('SELECT 
			o.id,
			o.userId,
			SUM(ol.quantity) AS `count`,
			SUM(p.price * ol.quantity) AS `total`
			FROM orders AS o
			LEFT JOIN order_lines AS ol ON ol.orderId=o.id
			LEFT JOIN products AS p ON p.id=ol.productId
			WHERE o.id=?
			GROUP BY o.id
			LIMIT 1',
			[$orderId]
		);

		$order = $order[0];

		$products = DB::select('SELECT
			p.id,
			p.title,
			pi.fileName
			FROM orders AS o
			LEFT JOIN order_lines AS ol ON ol.orderId=o.id
			LEFT JOIN products AS p ON p.id=ol.productId
			LEFT JOIN product_images AS pi ON pi.productId=p.id AND pi.primary=1
			WHERE o.id=?
			GROUP BY p.id',
			[$orderId]
		);

		$address = DB::select('SELECT
			a.id,
			a.type,
			CONCAT(a.firstName, " ", a.lastName) AS `name`,
			a.company,
			a.line1,
			a.city,
			a.region,
			co.name AS country,
			a.postCode,
			a.phone,
			a.email
			FROM orders AS o
			LEFT JOIN addresses AS a ON a.id=o.deliveryAddressId
			INNER JOIN countries AS co ON co.code=a.country
			WHERE o.id=?
			GROUP BY a.id
			LIMIT 1',
			[$orderId]
		);

		$address = $address[0];

		$invoice = Invoice::where('orderId', $orderId)->first();

		$invoice = $invoice->fileName;

		return view('public/order-successful', compact(
			'order',
			'products',
			'address',
			'invoice',
		));
	}
}