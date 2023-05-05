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

		if ($action == 'addresses') {
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
		
		} elseif ($action == 'payment') {

			$paymentMethods = [];

			// dd(auth()->user()->paymentMethods());

			foreach (auth()->user()->paymentMethods() as $i => $method) {
				$paymentMethods[$i] = [
					'id' => $method->id,
					'name' => $method->billing_details->name,
					'brand' => ucfirst($method->card->brand),
					'last4' => $method->card->last4,
					'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
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
	public function addPaymentMethod($id) {
		$result = auth()->user()->addPaymentMethod($id);

		return $result;
	}

	public function deletePaymentMethod($id) {
		$result = auth()->user()->deletePaymentMethod($id);

		return $result;
	}
}