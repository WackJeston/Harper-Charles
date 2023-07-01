<?php

namespace App\Http\Controllers;

use App\dataTable;

class AdminTestController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$dataTable = new DataTable('products');
		$dataTable->setQuery('SELECT * FROM products');


		$dataTable->addColumn('id', '#');
		$dataTable->addColumn('title', 'Title', 2);
		$dataTable->addColumn('productNumber', 'Product Number', 2);
		$dataTable->addColumn('price', 'Price', 1, false, 'currency');

		$dataTable->addButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$dataTable->addButton('duck-page/?', 'fa-solid fa-box', 'Close Record');

		$table = $dataTable->output();
		// $table = $dataTable->display();

		$table2 = new DataTable('categories');
		$table2->setQuery('SELECT * FROM product_categories');

		$table2->addColumn('id', '#');
		$table2->addColumn('title', 'Title');
		$table2->addColumn('subtitle', 'Subtitle', 2);
		$table2->addColumn('created_at', 'Created At');

		$table2->addButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');

		$table2->output();

		// dd($table);

    return view('admin/test', compact(
      'sessionUser',
			'dataTable',
			'table',
			'table2',
    ));
  }
}
