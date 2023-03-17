<?php
namespace App\Http\Controllers;

Use DB;
use App\Models\Address;

class CheckoutController extends Controller
{
  public function show() 
  {
    $sessionUser = auth()->user();

    return view('public/checkout', compact(
      'sessionUser',
    ));
  }

	public function addAddress($type, $addressData)
	{
		$address = [];
		$addressPre = explode('<&>', $addressData);

		foreach ($addressPre as $i => $line) {
			$linearray = explode('<=>', $line);
			$address[$linearray[0]] = $linearray[1];
		}

		// TODO: Validate address
		// TODO: Create address
		Address:create([
			'userId' => $sessionUser->id,
		]);

		return $address;
	}
}