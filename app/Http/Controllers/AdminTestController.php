<?php

namespace App\Http\Controllers;


class AdminTestController extends AdminController
{
  public function show()
  {
    $dataForm = new dataForm(request(), '/admin/test');

		$dataForm->addInput('text', 'firstName', 'First Name', null, 50, 1, true, 'John');
		$dataForm->addInput('text', 'lastName', 'Last Name', null, 50, 1, true, 'Doe');
		$dataForm->addInput('email', 'email', 'Email', null, 50, 1, true);
		$dataForm->addInput('password', 'password', 'Password', null, 100, 1, true);
		$dataForm->addInput('password', 'password_confirmation', 'Confirm Password', null, 100, 1, true);
		$dataForm->addInput('checkbox', 'realPerson', 'Real Person', null, 1, 1, true);

		$form = $dataForm->render();

    return view('admin/test', compact(
			'form',
    ));
  }
}
