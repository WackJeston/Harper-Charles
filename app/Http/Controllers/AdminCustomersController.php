<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\User;


class AdminCustomersController extends AdminController
{
  public function show()
  {
    $createForm = new DataForm(request(), '/customersCreate', 'Add');
		$createForm->addInput('text', 'firstname', 'First Name', null, 100, 1, true);
		$createForm->addInput('text', 'lastname', 'Last Name', null, 100, 1, true);
		$createForm->addInput('email', 'email', 'Email', null, 100, 1, true);
		$createForm->addInput('password', 'password', 'Password', null, 100, 6, true);
		$createForm = $createForm->render();

    $customersTable = new DataTable('users');
		$customersTable->setQuery('SELECT 
			u.*, 
			CONCAT(u.firstName, " ", u.lastName) AS `name`, 
			DATE_FORMAT(u.created_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`,
			COUNT(o.id) AS `orders`
			FROM users AS u 
			LEFT JOIN orders AS o ON o.userId = u.id
			WHERE u.admin = 0'
		);
		$customersTable->addColumn('id', '#');
		$customersTable->addColumn('name', 'Name');
		$customersTable->addColumn('email', 'Email', 2);
		$customersTable->addColumn('orders', 'Orders');
		$customersTable->addColumn('date', 'Created At', 2, true);
		$customersTable->addLinkButton('customer-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$customersTable = $customersTable->render();

    return view('admin/customers', compact(
			'createForm',
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

    return redirect('/admin/customers')->with('message', 'Customer created.');
  }
}
