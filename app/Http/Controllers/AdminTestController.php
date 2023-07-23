<?php

namespace App\Http\Controllers;

use App\dataForm;

class AdminTestController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$dataForm = new dataForm(request(), '/admin/test');

		$dataForm->addInput('text', 'firstName', 'First Name', null, true, 'John');
		$dataForm->addInput('text', 'lastName', 'Last Name', null, true, 'Doe');
		$dataForm->addInput('email', 'email', 'Email', null, true);
		$dataForm->addInput('password', 'password', 'Password', null, true);
		$dataForm->addInput('password', 'password_confirmation', 'Confirm Password', null, true);
		$dataForm->addInput('checkbox', 'realPerson', 'Real Person', null, true);

		$form = $dataForm->render();

    return view('admin/test', compact(
      'sessionUser',
			'form',
    ));
  }
}
