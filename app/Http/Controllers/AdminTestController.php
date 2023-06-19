<?php

namespace App\Http\Controllers;

use App\dataTable;

class AdminTestController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$dataTable = new DataTable();
		$dataTable->setQuery('SELECT * FROM products');

		$dataTable->addColumn('id', '#');
		$dataTable->addColumn('title', 'Title');
		$dataTable->addColumn('productNumber', 'Product Number');
		$dataTable->addColumn('price', 'Price');

		$table = $dataTable->output();

    return view('admin/test', compact(
      'sessionUser',
			'table'
    ));
  }
}
