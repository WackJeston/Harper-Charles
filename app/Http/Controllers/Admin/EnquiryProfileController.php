<?php
namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Enquiry;


class EnquiryProfileController extends AdminController
{
  public function show($id)
  {
		if (!$enquiry = Enquiry::find($id)) {
      return redirect('/admin/enquiries')->withErrors(['1' => 'Enquiry not found']);
    }

		$type = 'standard';

    return view('admin/enquiry-profile', compact(
      'enquiry',
			'type',
    ));
  }
}
