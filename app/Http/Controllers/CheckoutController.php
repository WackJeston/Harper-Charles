<?php
namespace App\Http\Controllers;

Use DB;
use App\Models\Address;
use Laravel\Cashier\Cashier;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineVariant;
use App\Models\OrderNote;
use App\Models\Payment;
use App\Models\User;

use App\Http\Api\InvoiceRenderer;



class CheckoutController extends PublicController
{
  public function show($action) 
  {
		$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();

		if (empty($order)) {
			return redirect('/basket');
		}

		$orderLinesCount = OrderLine::where('orderId', $order->id)->count();

		if ($orderLinesCount == 0) {
			return redirect('/basket');
		}

    switch ($action) {
			case 'address':
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
				$order->status = 'checkout-summary';
				$order->save();

				self::setTotal($order->id);

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
				}

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

				$checkout->orderNote = OrderNote::select('note')->where('orderId', $order->id)->where('primary', 1)->first();

				if (!empty($checkout->orderNote)) {
					$checkout->orderNote = $checkout->orderNote->note;
				}

				return view('public/checkout', compact(
					'action',
					'checkout',
				));
				break;
			
			case 'payment':
				$order->status = 'checkout-payment';
				$order->save();

				self::setTotal($order->id);

				if ($order->deliveryAddressId == null || !$billingAddress = Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->first()) {
					return redirect('/checkout/address')->withErrors(['1' => 'Please select an address.']);
				}

				$user = User::find(auth()->user()->id);

				$params = [
					'amount' => $order->total * 100,
					'currency' => 'gbp',
					'capture_method' => 'automatic',
					'automatic_payment_methods' => ['enabled' => true],
					'customer' => $user->stripe_id,
					'metadata' => [
						'order_id' => $order->id,
					],
				];

				\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				
				if (is_null($order->stripeIntentId)) {
					try {
						$intent = \Stripe\PaymentIntent::create($params);
					} catch (\Throwable $th) {
						$intent = false;
					} finally {
						$order->stripeIntentId = $intent->id;
						$order->save();
					}

				} else {
					$intent = \Stripe\PaymentIntent::retrieve($order->stripeIntentId);

					if ($intent->status == 'canceled') {
						try {
							$intent = \Stripe\PaymentIntent::create($params);
						} catch (\Throwable $th) {
							$intent = false;
						} finally {
							$order->stripeIntentId = $intent->id;
							$order->save();
						}
					}

					if ($intent->amount != $order->total * 100) {
						$intent->amount = $order->total * 100;
						$intent->save();
					}
				}

				Payment::create([
					'orderId' => $order->id,
					'stripeReference' => $intent->id,
					'type' => 'Register',
					'status' => $intent->status,
					'amount' => $intent->amount / 100,
					'captured' => $intent->amount_captured / 100,
				]);

				$scripts = [
					[
						'path' => 'https://js.stripe.com/v3/',
						'loadType' => '',
						'onLoad' => '',
						'location' => 'head',
					]
				];

				$paymentElementData = [
					'key' => env('STRIPE_KEY'),
					'clientSecret' => $intent->client_secret,
					'amount' => $order->total,
					'billingDetails' => [
						'name' => $billingAddress->firstName . ' ' . $billingAddress->lastName,
						'email' => $billingAddress->email,
						'phone' => $billingAddress->phone,
						'address' => [
							'line1' => $billingAddress->line1,
							'line2' => $billingAddress->line2,
							'city' => $billingAddress->city,
							'state' => $billingAddress->region,
							'postal_code' => $billingAddress->postCode,
							'country' => $billingAddress->country,
						],
					],
				];

				return view('public/checkout', compact(
					'action',
					'scripts',
					'paymentElementData',
				));
				break;
				
			default:
				return redirect('/checkout/address');
				break;
		}
  }

	public function setTotal(int $id) {
		$order = Order::find($id);

		$order->items = DB::select('SELECT
			COUNT(ol.id) AS items
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			GROUP BY ol.orderId
			LIMIT 1',
			[$order->id]
		)[0]->items;

		$lines = DB::select('SELECT
			ol.id,
			ol.price,
			ol.quantity
			FROM order_lines AS ol
			WHERE ol.orderId = ?',
			[$order->id]
		);

		foreach ($lines as $i => $line) {
			$orderLine = OrderLine::find($line->id);
			$orderLine->total = $line->price * $line->quantity;
			$orderLine->save();
		}

		$order->total = DB::select('SELECT
			SUM(ol.total) AS total
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			LIMIT 1',
			[$order->id]
		)[0]->total;

		$order->save();
	}


	// ADDRESSES --------------------------------------------------
	public function continueAddress($deliveryId)
	{
		$billingAddress = Address::where('userId', auth()->user()->id)->where('defaultBilling', 1)->first();

		Order::where('userId', auth()->user()->id)->where('type', 'basket')->update([
			'deliveryAddressId' => $deliveryId,
			'billingAddressId' => $billingAddress->id,
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


	// SUMMARY ---------------------------------------------------
	public function saveOrderNote(string $note)
	{
		$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();

		if (empty($order)) {
			return http_response_code(400);
		}

		$orderNote = OrderNote::where('orderId', $order->id)->where('primary', 1)->first();
		
		if (!empty($orderNote)) {
			$orderNote->note = $note;
			$orderNote->save();

		} else {
			$orderNote = OrderNote::create([
				'orderId' => $order->id,
				'userId' => auth()->user()->id,
				'primary' => 1,
				'note' => $note,
			]);
		}

		return true;
	}


	// PAYMENT --------------------------------------------------
	public function completeOrder()
	{
		$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();

		if (empty($order) || $order->status != 'checkout-payment') {
			return redirect('/basket');
		}

		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		
		$intent = \Stripe\PaymentIntent::retrieve($order->stripeIntentId);

		if ($intent->payment_method == null) {
			$method = null;
		} else {
			$paymentMethod = \Stripe\PaymentMethod::retrieve($intent->payment_method);
			$method = $paymentMethod->type;
		}

		if ($intent->latest_charge == null) {
			$detail = null;
			$receipt = null;
		} else {
			$charge = \Stripe\Charge::retrieve($intent->latest_charge);
			$detail = $charge->failure_message;
			$receipt = $charge->receipt_url;
		}

		Payment::create([
			'orderId' => $order->id,
			'stripeReference' => $intent->id,
			'type' => 'Capture',
			'status' => $intent->status,
			'detail' => $detail,
			'method' => $method,
			'amount' => $intent->amount / 100,
			'captured' => $intent->amount_received / 100,
		]);

		if ($intent->status != 'succeeded') {
			if (is_null($detail)) {
				return redirect('/checkout/payment')->withErrors(['1' => 'Something went wrong. Please review your order and try again.']);
			} else {
				return redirect('/checkout/payment')->withErrors(['1' => $detail]);
			}
		}

		$order->paymentMethodId = $intent->payment_method;
		$order->type = 'web';
		$order->status = 'new';
		$order->stripeReceipt = $receipt;
		$order->save();

		return redirect('/order-successful/' . $order->id);
	}


	// CONFIRMATION --------------------------------------------------
	public function orderSuccessful($orderId)
	{
		$order = DB::select('SELECT
			o.*
			FROM orders AS o
			WHERE o.id=?
			LIMIT 1',
			[$orderId]
		);

		if (empty($order)) {
			return redirect('/basket');

		} else {
			$payment = DB::select('SELECT
				p.*
				FROM payments AS p
				WHERE p.orderId=?
				ORDER BY p.id DESC
				LIMIT 1',
				[$orderId]
			);

			if (empty($payment) || $payment[0]->amount > $payment[0]->captured) {
				return redirect('/basket');
			}

			$order = $order[0];

			$order->billingAddress = DB::select('SELECT
				a.*
				FROM addresses AS a
				WHERE a.id = ?',
				[$order->billingAddressId]
			);

			$order->deliveryAddress = DB::select('SELECT
				a.*
				FROM addresses AS a
				WHERE a.id = ?',
				[$order->deliveryAddressId]
			);

			$order->deliveryAddress = $order->deliveryAddress[0];
			$order->billingAddress = $order->billingAddress[0];

			$order->lines = DB::select('SELECT
				ol.*,
				p.id AS productId,
				p.title,
				p.subtitle,
				IF(isnull(ol.assetId), a.fileName, a2.fileName) AS fileName
				FROM order_lines AS ol
				INNER JOIN products AS p ON p.id = ol.productId
				LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
				LEFT JOIN asset AS a ON a.id = pi.assetId
				LEFT JOIN asset AS a2 ON a2.id = ol.assetId
				WHERE ol.orderId = ?
				GROUP BY ol.id
				ORDER BY ol.created_at ASC',
				[$order->id]
			);

			$order->lines = cacheImages($order->lines, 600, 600, true, 'EFEFEF');

			foreach ($order->lines as $i => $line) {
				$order->lines[$i]->variants = DB::select('SELECT
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

		$invoice = Invoice::createInvoice($order->id);
		$invoice = cachePdf($invoice->fileName, true);

		return view('public/order-successful', compact(
			'order',
			'invoice',
		));
	}
}