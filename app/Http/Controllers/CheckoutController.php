<?php
namespace App\Http\Controllers;

Use DB;
use App\Models\Address;
use App\Models\User;
use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\CheckoutProductsVariant;



class CheckoutController extends Controller
{
  public function show($action) 
  {
    $sessionUser = auth()->user();

		switch ($action) {
			default: // addresses
				$deliveryAddresses = Address::where('userId', $sessionUser->id)->where('type', 'delivery')->orderBy('defaultShipping', 'desc')->get();
				$defaultDelivery = $deliveryAddresses->where('defaultShipping', 1)->first();
				$defaultDelivery = isset($defaultDelivery->id) ? $defaultDelivery->id : null;

				$billingAddresses = Address::where('userId', $sessionUser->id)->where('type', 'billing')->orderBy('defaultBilling', 'desc')->get();
				$defaultBilling = $billingAddresses->where('defaultBilling', 1)->first();
				$defaultBilling = isset($defaultBilling->id) ? $defaultBilling->id : null;

				$countries = DB::select('SELECT * FROM countries ORDER BY name ASC');

				return view('public/checkout', compact(
					'sessionUser',
					'action',
					'deliveryAddresses',
					'defaultDelivery',
					'billingAddresses',
					'defaultBilling',
					'countries',
				));
				break;

			case 'payment':
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
					[$sessionUser->id]
				);

				$billingAddress = $billingAddress[0];

				return view('public/checkout', compact(
					'sessionUser',
					'action',
					'billingAddress',
					'paymentMethods',
				));
				break;

			case 'review':
				$checkout = DB::select('SELECT 
					c.id,
					SUM(cp.quantity) AS `count`,
					SUM(p.price * cp.quantity) AS `total`
					FROM checkout AS c
					LEFT JOIN checkout_products AS cp ON cp.checkoutId=c.id
					LEFT JOIN products AS p ON p.id=cp.productId
					WHERE c.userId=?
					GROUP BY c.id',
					[$sessionUser->id]
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
					[$sessionUser->id]
				);

				$addresses = DB::select('SELECT
					a.id,
					a.type,
					CONCAT(a.firstName, " ", a.lastName) AS `name`,
					a.company,
					a.line1,
					a.city,
					a.region,
					a.country,
					a.postCode,
					a.phone,
					a.email
					FROM checkout AS c
					LEFT JOIN addresses AS a ON a.id=c.deliveryAddressId OR a.id=c.billingAddressId
					WHERE c.userId=?
					GROUP BY a.id',
					[$sessionUser->id]
				);

				$method = $sessionUser->findPaymentMethod(Checkout::where('userId', $sessionUser->id)->first()->paymentMethodId);

				$paymentMethod = [
					'id' => $method->id,
					'brand' => ucfirst($method->card->brand),
					'last4' => $method->card->last4,
					'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
					'postcode' => $method->billing_details->address->postal_code,
				];

				return view('public/checkout', compact(
					'sessionUser',
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

	public function addAddress($type, $addressData)
	{
		$address = [];
		$addressPre = explode('<&>', $addressData);

		foreach ($addressPre as $i => $line) {
			$linearray = explode('<=>', $line);
			$address[$linearray[0]] = $linearray[1];
		}

		$defaultBilling = isset($address['defaultbilling']) && $address['defaultbilling'] == 'true' ? 1 : 0;
		$defaultDelivery = isset($address['defaultdelivery']) && $address['defaultdelivery'] == 'true' ? 1 : 0;

		if ($defaultBilling) {
			Address::where('userId', auth()->user()->id)->where('type', 'billing')->update(['defaultBilling' => 0]);
		}
		if ($defaultDelivery) {
			Address::where('userId', auth()->user()->id)->where('type', 'delivery')->update(['defaultShipping' => 0]);
		}

		$company = !empty($address['company']) ? $address['company'] : null;
		$line2 = !empty($address['line2']) ? $address['line2'] : null;
		$line3 = !empty($address['line3']) ? $address['line3'] : null;
		$region = !empty($address['region']) ? $address['region'] : null;

		$result = Address::create([
			'userId' => auth()->user()->id,
			'type' => $type,
			'defaultBilling' => $defaultBilling,
			'defaultShipping' => $defaultDelivery,
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

	public function defaultAddress($type, $id)
	{
		if ($type == 'billing') {
			Address::where('userId', auth()->user()->id)->where('type', 'billing')->update(['defaultBilling' => 0]);

			$default = Address::where('id', $id)->first();
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

		} else {
			Address::where('userId', auth()->user()->id)->where('type', 'delivery')->update(['defaultShipping' => 0]);
			Address::where('id', $id)->update(['defaultShipping' => 1]);
		}
	}

	public function deleteAddress($id)
	{
		$address = Address::where('id', $id)->first();
		$address->delete();

		return true;
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
}