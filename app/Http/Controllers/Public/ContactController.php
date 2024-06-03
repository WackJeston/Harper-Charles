<?php
namespace App\Http\Controllers\Public;

Use DB;
use Illuminate\Http\Request;
use App\Models\Enquiry;


class ContactController extends PublicController
{
  public function show()
  {
		$addressPre = DB::select('SELECT 
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

    $address = [];

    foreach ($addressPre as $i => $value) {
      $address[$value->type] = $value->value;
    }

    $coordinatesPre = DB::select('SELECT
			c.type, 
			c.value
			FROM contact AS c
			WHERE c.type IN ("lat", "lng")
		');

		$coordinates = [];

		if (isset($coordinatesPre[0]->value)) {
			$coordinates['lat'] = (float) $coordinatesPre[0]->value;
		}

		if (isset($coordinatesPre[1]->value)) {
			$coordinates['lng'] = (float) $coordinatesPre[1]->value;
		}
    $contact = [];

    $contact['email'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "email"'
    );

    $contact['phone'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "phone"'
    );

    $contact['url'] = DB::select('SELECT 
      c.type, 
      c.value,
			c.label
      FROM contact AS c
      WHERE c.type = "url"'
    );

    return view('public/contact', compact(
			'address',
			'coordinates',
      'contact',
    ));
  }


	public function createEnquiry(Request $request) 
	{
		$request->validate([
      'name' => 'required|max:255',
      'email' => 'required|max:255',
      'phone' => 'max:20',
			'subject' => 'max:255',
			'message' => 'required|max:1000',
    ]);

    Enquiry::create([
			'type' => 'standard',
      'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'subject' => $request->subject,
			'message' => $request->message,
    ]);

    return redirect('/contact')->with('message', 'Enquiry sent.');
	}
}
