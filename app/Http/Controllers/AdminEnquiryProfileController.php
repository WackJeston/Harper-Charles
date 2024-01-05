<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminEnquiryProfileController extends Controller
{
  public function show($id)
  {
		if (!$enquiry = Enquiry::find($id)) {
      return redirect('/admin/enquiries')->withErrors(['1' => 'Enquiry not found']);
    }

    return view('admin/enquiry-profile', compact(
      'enquiry',
			'type',
    ));
  }
}
