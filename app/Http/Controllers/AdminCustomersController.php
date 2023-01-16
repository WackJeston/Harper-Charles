<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;


class AdminCustomersController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $customers = DB::select('SELECT
      u.id,
      CONCAT(u.firstName, " ", u.lastName) AS name,
      u.email,
      u.created_at
      FROM users AS u
      WHERE u.admin=0
    ');

    return view('admin/customers', compact(
      'sessionUser',
      'customers',
    ));
  }


  public function create(Request $request)
  {
    $request->validate([
      'firstname' => 'required|max:100',
      'lastname' => 'required|max:100',
      'email' => ['required', 'email', 'max:100', Rule::unique('users')],
      'password' => 'required|min:6|max:100'
    ]);

    User::create([
      'admin' => 0,
      'firstname' => ucfirst($request->firstname),
      'lastname' => ucfirst($request->lastname),
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
    ]);

    return redirect('/admin/customers')->with('message', 'Customer created successfully.');
  }
}
