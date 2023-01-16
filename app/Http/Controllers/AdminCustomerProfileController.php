<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;


class AdminCustomerProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

    if (User::find($id) == null) {
      return redirect('/admin/customers');
    }

    $customer = DB::select(sprintf('SELECT
      id,
      firstName,
      lastName,
      email,
      password
      FROM users
      WHERE id = %d
      LIMIT 1
    ', $id));

    $customer = $customer[0];

    return view('admin/customer-profile', compact(
      'sessionUser',
      'customer',
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

    return redirect("/admin/customer-profile/$id")->with('message', 'Customer updated successfully.');
  }


  public function delete($id)
  {
    User::find($id)->delete();

    return redirect("/admin/customers")->with('message', 'Customer deleted successfully.');
  }
}
