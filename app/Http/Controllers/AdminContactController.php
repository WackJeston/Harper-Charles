<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Contact;

class AdminContactController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $contactRecords = Contact::select('id', 'type', 'value')->get();

    $contact = [];

    foreach ($contactRecords as $record) {
      if($record->type != 'email' && $record->type != 'phone') {
        $contact[$record->type] = $record->value;
      } else {
        $contact[$record->type][] = $record;
      }
    }

		$editForm = new dataForm(request(), '/contactUpdateAddress', 'Save', 'updateAddressMap()');
		$editForm->addInput('text', 'line1', 'Line 1', $contact['line1'] ?? null, 200, 1);
		$editForm->addInput('text', 'line2', 'Line 2', $contact['line2'] ?? null, 200, 0);
		$editForm->addInput('text', 'line3', 'Line 3', $contact['line3'] ?? null, 200, 0);
		$editForm->addInput('text', 'city', 'City', $contact['city'] ?? null, 200, 1);
		$editForm->addInput('text', 'region', 'Region', $contact['region'] ?? null, 200, 1);
		$editForm->addInput('text', 'country', 'Country', $contact['country'] ?? null, 200, 1);
		$editForm->addInput('text', 'postcode', 'Postcode', $contact['postcode'] ?? null, 200, 1);
		$editForm = $editForm->render();

		$emailForm = new DataForm(request(), '/contactCreateEmail', 'Add');
		$emailForm->addInput('email', 'email', 'Email', null, 200, 1, true);
		$emailForm = $emailForm->render();

		$emailsTable = new DataTable('contact_REF_1');
		$emailsTable->setQuery('SELECT * FROM contact WHERE type = "email"');
		$emailsTable->addColumn('id', '#');
		$emailsTable->addColumn('value', 'Email');
		$emailsTable->addColumn('updated_at', 'Last Updated', 1, true);
		$emailsTable->addJsButton('showDeleteWarning', ['string:Email', 'record:id', 'url:/contactDeleteEmail/?'], 'fa-solid fa-trash-can', 'Delete Email');
		$emailsTable = $emailsTable->render();

		$phoneForm = new DataForm(request(), '/contactCreatePhone', 'Add');
		$phoneForm->addInput('tel', 'phone', 'Phone', null, 20, 1, true);
		$phoneForm = $phoneForm->render();

		$phonesTable = new DataTable('contact_REF_2');
		$phonesTable->setQuery('SELECT * FROM contact WHERE type = "phone"');
		$phonesTable->addColumn('id', '#');
		$phonesTable->addColumn('value', 'Phone');
		$phonesTable->addColumn('updated_at', 'Last Updated', 1, true);
		$phonesTable->addJsButton('showDeleteWarning', ['string:Phone', 'record:id', 'url:/contactDeletePhone/?'], 'fa-solid fa-trash-can', 'Delete Phone');
		$phonesTable = $phonesTable->render();

    return view('admin/contact', compact(
      'sessionUser',
      'contact',
			'editForm',
			'emailForm',
			'emailsTable',
			'phoneForm',
			'phonesTable',
    ));
  }

  public function updateAddress(Request $request)
  {
    foreach ($request->all() as $i => $value) {
      if($i != '_token') {
        $record = Contact::where('type', $i)->first();

        if($record) {
          if ($value == '') {
            $record->delete();
          } else {
            $record->value = $value;
            $record->save();
          }
        } elseif(!empty($value)) {
          $record = new Contact;
          $record->type = $i;
          $record->value = $value;
          $record->save();
        }
      }
    }

    return redirect("/admin/contact")->with('message', 'Address updated successfully.');
  }

	public function uploadLatLng($lat, $lng) {
		$record = Contact::where('type', 'lat')->first();

    if($record) {
      $record->value = $lat;
      $record->save();
    } else {
      $record = new Contact;
      $record->type = 'lat';
      $record->value = $lat;
      $record->save();
    }

    $record = Contact::where('type', 'lng')->first();

    if($record) {
      $record->value = $lng;
      $record->save();
    } else {
      $record = new Contact;
      $record->type = 'lng';
      $record->value = $lng;
      $record->save();
    }
	}

  public function createEmail(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email', 'max:200', Rule::unique('contact', 'value')],
    ]);

    $record = new Contact;
    $record->type = 'email';
    $record->value = strtolower($request->email);
    $record->save();

    return redirect("/admin/contact")->with('message', 'Email created successfully.');
  }

  public function deleteEmail($id)
  {
    $record = Contact::find($id);

    if($record) {
      $record->delete();
    }

    return redirect("/admin/contact")->with('message', 'Email deleted successfully.');
  }

  public function createPhone(Request $request)
  {
    $request->validate([
      'phone' => ['required', Rule::unique('contact', 'value')],
    ]);

    $record = new Contact;
    $record->type = 'phone';
    $record->value = $request->phone;
    $record->save();

    return redirect("/admin/contact")->with('message', 'Phone created successfully.');
  }

  public function deletePhone($id)
  {
    $record = Contact::find($id);

    if($record) {
      $record->delete();
    }

    return redirect("/admin/contact")->with('message', 'Phone deleted successfully.');
  }
}
