<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Enquiry;


class AdminEnquiryProfileController extends AdminController
{
  public function show($id)
  {
		$type = 'standard';

    $enquiry = Enquiry::find($id);

    return view('admin/enquiry-profile', compact(
      'enquiry',
			'type',
    ));
  }
}
