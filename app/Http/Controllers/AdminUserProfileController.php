<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\User;


class AdminUserProfileController extends AdminController
{
  public function show($id)
  {
    if (User::find($id) == null) {
      return redirect('/admin/users')->withErrors(['1' => 'User not found']);
    }

    $user = DB::select(sprintf('SELECT
      *
      FROM users
      WHERE id = %d
      LIMIT 1
    ', $id));

    $user = $user[0];

		$editForm = new dataForm(request(), sprintf('/user-profileUpdate/%d', $id), 'Update');
		$editForm->addInput('text', 'firstname', 'First Name', $user->firstName, 255, 1, true);
		$editForm->addInput('text', 'lastname', 'Last Name', $user->lastName, 255, 1, true);
		$editForm->addInput('email', 'email', 'Email', $user->email, 255, 1, true);
		$editForm->addInput('password', 'password', 'Password', null, 255, 6, false, 'New Password');
		$editForm = $editForm->render();

		$ordersTable = new DataTable('orders');
		$ordersTable->setQuery(sprintf('SELECT o.* FROM orders AS o WHERE o.userId = %d', $id));
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('status', 'Status');
		$ordersTable->addColumn('total', 'Total');
		$ordersTable->addColumn('created_at', 'Created', 1, true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

    return view('admin/user-profile', compact(
      'user',
			'editForm',
			'ordersTable',
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

    return redirect("/admin/user-profile/$id")->with('message', 'User updated.');
  }


  public function delete($id)
  {
    User::find($id)->delete();

    return redirect("/admin/users")->with('message', 'User deleted.');
  }
}
