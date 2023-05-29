<?php

namespace App\Http\Controllers;

use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Order;


class AccountController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$orders = User::getOrders($sessionUser->id);

    return view('public/account', compact(
      'sessionUser',
			'orders',
    ));
  }

  public function update(Request $request, $id)
  {
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

    return redirect("/account")->with('message', 'User updated successfully.');
  }
}
