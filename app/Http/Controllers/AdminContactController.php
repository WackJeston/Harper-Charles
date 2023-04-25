<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    return view('admin/contact', compact(
      'sessionUser',
      'contact',
    ));
  }

  public function updateAddress(Request $request, $lat, $lng)
  {
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
