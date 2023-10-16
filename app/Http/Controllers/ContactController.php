<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Http\Request;
use App\Models\Enquiry;


class ContactController extends Controller
{
  public function show()
  {
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

    return redirect('/contact')->with('message', 'Enquiry successfully sent.');
	}
}
