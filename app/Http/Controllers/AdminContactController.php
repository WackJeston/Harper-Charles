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
    $contactRecords = Contact::select('id', 'type', 'value', 'label')->get();

    $contact = [];

    foreach ($contactRecords as $record) {
      if($record->type != 'email' && $record->type != 'phone' && $record->type != 'url') {
        $contact[$record->type] = $record->value;
      } else {
        $contact[$record->type][] = [
					'value' => $record->value,
					'label' => $record->label,
				];
      }
    }

		$editForm = new DataForm(request(), '/contactUpdateAddress', 'Save', 'updateAddressMap()');
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
		$emailForm->addInput('text', 'label', 'Label', null, 255, 0);
		$emailForm = $emailForm->render();

		$emailsTable = new DataTable('contact_REF_1');
		$emailsTable->setQuery('SELECT * FROM contact WHERE type = "email"');
		$emailsTable->addColumn('id', '#');
		$emailsTable->addColumn('value', 'Email');
		$emailsTable->addColumn('label', 'Label');
		$emailsTable->addColumn('updated_at', 'Last Updated', 1, true);
		$emailsTable->addJsButton('showDeleteWarning', ['string:Email', 'record:id', 'url:/contactDeleteEmail/?'], 'fa-solid fa-trash-can', 'Delete Email');
		$emailsTable = $emailsTable->render();

		$phoneForm = new DataForm(request(), '/contactCreatePhone', 'Add');
		$phoneForm->addInput('tel', 'phone', 'Phone', null, 20, 1, true);
		$phoneForm->addInput('text', 'label', 'Label', null, 255, 0);
		$phoneForm = $phoneForm->render();

		$phonesTable = new DataTable('contact_REF_2');
		$phonesTable->setQuery('SELECT * FROM contact WHERE type = "phone"');
		$phonesTable->addColumn('id', '#');
		$phonesTable->addColumn('value', 'Phone');
		$phonesTable->addColumn('label', 'Label');
		$phonesTable->addColumn('updated_at', 'Last Updated', 1, true);
		$phonesTable->addJsButton('showDeleteWarning', ['string:Phone', 'record:id', 'url:/contactDeletePhone/?'], 'fa-solid fa-trash-can', 'Delete Phone');
		$phonesTable = $phonesTable->render();

		$urlForm = new DataForm(request(), '/contactCreateUrl', 'Add');
		$urlForm->addInput('url', 'url', 'URL', null, 200, 1, true);
		$urlForm->addInput('text', 'label', 'Label', null, 255, 0);
		$urlForm = $urlForm->render();

		$urlsTable = new DataTable('contact_REF_3');
		$urlsTable->setQuery('SELECT * FROM contact WHERE type = "url"');
		$urlsTable->addColumn('id', '#');
		$urlsTable->addColumn('value', 'URL');
		$urlsTable->addColumn('label', 'Label');
		$urlsTable->addColumn('updated_at', 'Last Updated', 1, true);
		$urlsTable->addJsButton('showDeleteWarning', ['string:URL', 'record:id', 'url:/contactDeleteUrl/?'], 'fa-solid fa-trash-can', 'Delete URL');
		$urlsTable = $urlsTable->render();

    return view('admin/contact', compact(
      'contact',
			'editForm',
			'emailForm',
			'emailsTable',
			'phoneForm',
			'phonesTable',
			'urlForm',
			'urlsTable',
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

    return redirect("/admin/contact")->with('message', 'Address updated.');
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
		$record->label = $request->label;
    $record->save();

    return redirect("/admin/contact")->with('message', 'Email created.');
  }

  public function deleteEmail($id)
  {
    $record = Contact::find($id);

    if($record) {
      $record->delete();
    }

    return redirect("/admin/contact")->with('message', 'Email deleted.');
  }

  public function createPhone(Request $request)
  {
    $request->validate([
      'phone' => ['required', Rule::unique('contact', 'value')],
    ]);

    $record = new Contact;
    $record->type = 'phone';
    $record->value = $request->phone;
		$record->label = $request->label;
    $record->save();

    return redirect("/admin/contact")->with('message', 'Phone created.');
  }

  public function deletePhone($id)
  {
    $record = Contact::find($id);

    if($record) {
      $record->delete();
    }

    return redirect("/admin/contact")->with('message', 'Phone deleted.');
  }

  public function createUrl(Request $request)
  {
    $request->validate([
      'url' => ['required', Rule::unique('contact', 'value')],
    ]);

    $record = new Contact;
    $record->type = 'url';
    $record->value = $request->url;
		$record->label = $request->label;
    $record->save();

    return redirect("/admin/contact")->with('message', 'URL created.');
  }

  public function deleteUrl($id)
  {
    $record = Contact::find($id);

    if($record) {
      $record->delete();
    }

    return redirect("/admin/contact")->with('message', 'URL deleted.');
  }
}
