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

		$dataTable->addButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');

		$table = $dataTable->output();

		// dd($table);

    return view('admin/test', compact(
      'sessionUser',
			'table'
    ));
  }
}
