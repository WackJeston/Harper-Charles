<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\dataTable;
use App\Models\User;


class AdminCustomersController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $customersTable = new DataTable();
		$customersTable->setQuery('SELECT *, CONCAT(firstName, " ", lastName) AS `name` FROM users WHERE admin = 0');

		$customersTable->addColumn('id', '#');
		$customersTable->addColumn('name', 'Name');
		$customersTable->addColumn('email', 'Email', 2);
		$customersTable->addColumn('created_at', 'Created');

		$customersTable->addLinkButton('customer-profile/?', 'fa-solid fa-folder-open', 'Open Record');

		$customersTable = $customersTable->render();

    return view('admin/customers', compact(
      'sessionUser',
      'customersTable',
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
