<?php

namespace App\Http\Controllers;

use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderNote;
use App\Models\Invoice;
use App\Models\Address;


class AccountController extends Controller
{
  public function show() {
		$sessionUser = auth()->user();

		$action = 'account';

		$orders = User::getOrders($sessionUser->id);

    return view('public/account', compact(
			'sessionUser',
			'action',
			'orders',
    ));
  }

  public function update(Request $request, int $id) {
    $request->validate([
      'firstname' => 'max:100',
      'lastname' => 'max:100',
      'email' => ['email', 'max:100', Rule::unique('users')->ignore($id)],
      'password' => 'nullable|min:6|max:100'
    ]);

    User::where('id', $id)->update([
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'email' => $request->email,
    ]);

    if ($request->password) {
      User::where('id', $id)->update([
        'password' => Hash::make($request->password),
      ]);
    }

    return redirect("/account")->with('message', 'User updated.');
  }

	public function orderShow(int $orderId) {
		$sessionUser = auth()->user();

		$action = 'order';

		if ($order = Order::getOrder($orderId)) {
			$invoice = Invoice::where('orderId', $orderId)->first();
			$notes = Order::getNotes($orderId);

			return view('public/account', compact(
				'sessionUser',
				'action',
				'order',
				'invoice',
				'notes',
			));

		} else {
			return redirect("/account")->withErrors(['1' => 'Order not found.']);
		}
	}

	public function orderAddNote(Request $request, int $orderId) {
		if ($order = Order::find($orderId)) {
			$request->validate([
				'note' => 'max:4000',
			]);
	
			OrderNote::create([
				'admin' => 0,
				'orderId' => $orderId,
				'note' => $request->note,
			]);
	
			return redirect("/account/order/" . $orderId)->with('message', 'Note added.');

		} else {
			return redirect("/account")->withErrors(['1' => 'Order not found.']);
		}

		
	}
}
