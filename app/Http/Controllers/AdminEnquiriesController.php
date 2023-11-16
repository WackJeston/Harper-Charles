<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\Models\Enquiry;


class AdminEnquiriesController extends Controller
{
  public function show()
  {
		$type = 'standard';

    $enquiriesTable = new DataTable('enquiry');
		$enquiriesTable->setQuery('SELECT * FROM enquiry WHERE `type` = "standard"', [], 'id', 'DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 2, true);
		$enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 2);
		$enquiriesTable->addColumn('created_at', 'Date', 2, true);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

    return view('admin/enquiries', compact(
			'type',
      'enquiriesTable',
    ));
  }
}
