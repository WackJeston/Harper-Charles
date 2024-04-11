<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;
use App\DataForm;
use App\Models\Enquiry;
use Illuminate\Http\Request;


class AdminEnquiriesController extends AdminController
{
  public function show()
  {
		$type = 'standard';

		if (session()->has('admin-enquiries-search')) {
			$formValue = session()->get('admin-enquiries-search')[0];
			$query = session()->get('admin-enquiries-search')[1];
		
		} else {
			$formValue = [
				'search' => null,
				'type' => 'all',
			];
			$query = 'SELECT * FROM enquiry';
		}

		$searchForm = new DataForm(request(), '/enquiriesSearch', 'Search');
		$searchForm->addInput('text', 'search', 'Search', $formValue['search'], 255, 0);
		$searchForm->addInput('select', 'type', 'Type', $formValue['type'], null, null, false, null, [], false);
		$searchForm->populateOptions('type', [
			[
				'value' => 'all',
				'label' => '',
			],
			[
				'value' => 'standard',
				'label' => 'Standard',
			],
			[
				'value' => 'complaints',
				'label' => 'Compaints',
			],
			[
				'value' => 'feedback',
				'label' => 'Feedback',
			],
		], false);
		$searchForm = $searchForm->render();

    $enquiriesTable = new DataTable('enquiry');
		$enquiriesTable->setQuery($query, [], 'id', 'DESC');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('name', 'Name', 2, true);
		$enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 2);
		$enquiriesTable->addColumn('created_at', 'Date', 2, true);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

    return view('admin/enquiries', compact(
			'type',
			'searchForm',
      'enquiriesTable',
    ));
  }

	public function search(Request $request)
	{
		$request->validate([
			'search' => 'max:255',
		]);

		$query = 'SELECT * FROM enquiry WHERE true';

		if (!empty($request->search)) {
			$query .= sprintf(' AND (id LIKE "%%%1$s%%" OR `name` LIKE "%%%1$s%%" OR email LIKE "%%%1$s%%" OR `subject` LIKE "%%%1$s%%")', $request->search);
			
			$explode = str_split($query);

			foreach ($explode as $i => $character) {
				if ($character == '%') {
					$explode[$i] = '%%';
				}
			}

			$query = implode($explode);
		}

		if ($request->type != 'all') {
			$query .= sprintf(' AND `type` = "%s"', $request->type);
		}

		$values = [
			'search' => $request->search,
			'type' => $request->type,
		];
			
		session()->put('admin-enquiries-search', [$values, $query]);

		return redirect('/admin/enquiries');
	}
}
