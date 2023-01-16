<?php
namespace App\Http\Controllers;

Use DB;


class ContactController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $contactPre = DB::select('SELECT 
      c.type, 
      c.value
      FROM contact AS c
      WHERE c.type IN
      (
        "line1",
        "line2",
        "city",
        "region",
        "postcode",
        "lat",
        "lng"
      )'
    );

    $contact = [];

    foreach ($contactPre as $i => $value) {
      $contact[$value->type] = $value->value;
    }

    $contact['lat'] = (float) $contact['lat'];
    $contact['lng'] = (float) $contact['lng'];

    $contact['email'] = DB::select('SELECT 
      c.type, 
      c.value
      FROM contact AS c
      WHERE c.type = "email"'
    );

    $contact['phone'] = DB::select('SELECT 
      c.type, 
      c.value
      FROM contact AS c
      WHERE c.type = "phone"'
    );

    return view('public/contact', compact(
      'sessionUser',
      'contact',
    ));
  }
}
