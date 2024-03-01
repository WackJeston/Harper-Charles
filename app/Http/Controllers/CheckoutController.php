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
			// default: // addresses
			case 'addresses':
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

			case 'payment':
				$checkout = Checkout::where('userId', auth()->user()->id)->first();

				if ($checkout->billingAddressId == null || $checkout->deliveryAddressId == null) {
					return redirect('/checkout/addresses')->withErrors(['1' => 'Please select an address.']);
				}

				$paymentMethods = [];

				foreach (auth()->user()->paymentMethods() as $i => $method) {
					$paymentMethods[$i] = [
						'id' => $method->id,
						'brand' => ucfirst($method->card->brand),
						'last4' => $method->card->last4,
						'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
						'postcode' => $method->billing_details->address->postal_code,
					];
				};

				$billingAddress = DB::select('SELECT
					a.*
					FROM addresses AS a
					INNER JOIN checkout AS c ON c.billingAddressId = a.id
					WHERE c.userId = ?
					LIMIT 1', 
					[auth()->user()->id]
				);

				$billingAddress = $billingAddress[0];

				$payment = auth()->user()->pay(
					$checkout->total * 100
				);
				$clientSecret = $payment->client_secret;

				$total = $checkout->total;

				return view('public/checkout', compact(
					'action',
					'billingAddress',
					'paymentMethods',
					'clientSecret',
					'total',
				));
				break;

			case 'review':
				$checkout = Checkout::where('userId', auth()->user()->id)->first();

				if ($checkout->billingAddressId == null || $checkout->deliveryAddressId == null) {
					return redirect('/checkout/addresses')->withErrors(['1' => 'Please select an address.']);

				} elseif ($checkout->paymentMethodId == null) {
					return redirect('/checkout/payment')->withErrors(['1' => 'Please select a payment method.']);
				}


				$checkout = DB::select('SELECT 
					c.id,
					c.userId,
					SUM(cp.quantity) AS `count`,
					SUM(p.price * cp.quantity) AS `total`
					FROM checkout AS c
					LEFT JOIN checkout_products AS cp ON cp.checkoutId=c.id
					LEFT JOIN products AS p ON p.id=cp.productId
					WHERE c.userId=?
					GROUP BY c.id',
					[auth()->user()->id]
				);

				$checkout = $checkout[0];

				$products = DB::select('SELECT
					p.id,
					p.title,
					p.subtitle,
					p.price,
					cp.quantity,
					pi.fileName,
					GROUP_CONCAT(pv2.title, ": ", pv.title) AS `variants`
					FROM checkout AS c
					LEFT JOIN checkout_products AS cp ON cp.checkoutId=c.id
					LEFT JOIN products AS p ON p.id=cp.productId
					LEFT JOIN product_images AS pi ON pi.productId=p.id AND pi.primary=1
					LEFT JOIN checkout_product_variants AS cpv ON cpv.checkoutProductId=cp.id
					LEFT JOIN product_variants AS pv ON pv.id=cpv.variantId
					LEFT JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId
					WHERE c.userId=?
					GROUP BY p.id',
					[auth()->user()->id]
				);

				$addresses = DB::select('SELECT
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
					FROM checkout AS c
					LEFT JOIN addresses AS a ON a.id=c.deliveryAddressId OR a.id=c.billingAddressId
					INNER JOIN countries AS co ON co.code=a.country
					WHERE c.userId=?
					GROUP BY a.id',
					[auth()->user()->id]
				);

				$method = auth()->user()->findPaymentMethod(Checkout::where('userId', auth()->user()->id)->first()->paymentMethodId);

				$paymentMethod = [
					'id' => $method->id,
					'brand' => ucfirst($method->card->brand),
					'last4' => $method->card->last4,
					'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
					'postcode' => $method->billing_details->address->postal_code,
				];

				return view('public/checkout', compact(
					'action',
					'checkout',
					'products',
					'addresses',
					'paymentMethod',
				));
				break;
		}
  }


	// ADDRESSES --------------------------------------------------
	public function continueAddress($delivery, $billing)
	{
		Checkout::where('userId', auth()->user()->id)->update([
			'deliveryAddressId' => $delivery,
			'billingAddressId' => $billing,
			'status' => 'payment'
		]);

		return redirect('/checkout/payment');
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

		$result = Address::create([
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
		]);

		return $result;
	}

	public function setBillingAddress(int $id): bool
	{
		Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->update(['defaultBilling' => 0]);

		if ($default = Address::find($id)) {
			$default->update(['defaultBilling' => 1]);

			$options = [
				'address' => [
					'city' => $default->city,
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
			exit;
		}

		return false;
	}	


	// PAYMENT --------------------------------------------------
	public function continuePayment($paymentMethod)
	{
		Checkout::where('userId', auth()->user()->id)->update([
			'paymentMethodId' => $paymentMethod,
			'status' => 'review',
		]);

		return redirect('/checkout/review');
	}

	public function addPaymentMethod($id) {
		$result = auth()->user()->addPaymentMethod($id);

		return $result;
	}

	public function deletePaymentMethod($id) {
		$result = auth()->user()->deletePaymentMethod($id);

		return $result;
	}


	// REVIEW --------------------------------------------------
	public function continueReview(int $userId = 0)
	{
		$orderId = Order::createOrder($userId);

		if ($orderId == 0) {
			return redirect('/checkout/review')->withErrors(['1' => 'Something went wrong. Please review your order and try again.']);
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