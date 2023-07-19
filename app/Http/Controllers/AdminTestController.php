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

		$dataTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$dataTable->addLinkButton('duck-page/?', 'fa-solid fa-box', 'Close Record');

		$table2 = new DataTable('categories');
		$table2->setQuery('SELECT * FROM product_categories');

		$table2->addColumn('id', '#');
		$table2->addColumn('title', 'Title');
		$table2->addColumn('subtitle', 'Subtitle', 2);
		$table2->addColumn('created_at', 'Created At');

		$table2->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');

		// dd($table);

    return view('admin/test', compact(
      'sessionUser',
			'dataTable',
			'table',
			'table2',
    ));
  }
}
