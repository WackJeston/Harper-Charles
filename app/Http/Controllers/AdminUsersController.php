<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\User;


class AdminUsersController extends AdminController
{
  public function show()
  {
		$createForm = new DataForm(request(), '/usersCreate', 'Add');
		$createForm->addInput('text', 'firstname', 'First Name', null, 100, 1, true);
		$createForm->addInput('text', 'lastname', 'Last Name', null, 100, 1, true);
		$createForm->addInput('email', 'email', 'Email', null, 100, 1, true);
		$createForm->addInput('password', 'password', 'Password', null, 100, 6, true);
		$createForm = $createForm->render();

    $usersTable = new DataTable('users');
		$usersTable->setQuery('SELECT 
			u.*, 
			CONCAT(u.firstName, " ", u.lastName) AS `name`, 
			DATE_FORMAT(u.created_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`,
			COUNT(o.id) AS `orders`
			FROM users AS u 
			LEFT JOIN orders AS o ON o.userId = u.id
			WHERE u.admin = 1'
		);
		$usersTable->addColumn('id', '#');
		$usersTable->addColumn('name', 'Name');
		$usersTable->addColumn('email', 'Email', 2);
		$usersTable->addColumn('orders', 'Orders');
		$usersTable->addColumn('date', 'Created At', 2, true);
		$usersTable->addLinkButton('user-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$usersTable = $usersTable->render();

    return view('admin/users', compact(
			'createForm',
      'usersTable',
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
      'admin' => 1,
      'firstname' => ucfirst($request->firstname),
      'lastname' => ucfirst($request->lastname),
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
    ]);

    return redirect('/admin/users')->with('message', 'User created.');
  }
}
