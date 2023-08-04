<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\dataTable;
use App\dataForm;
use App\Models\Orders;


class AdminOrderProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();
		
		$order = DB::select('SELECT 
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `user`,
			IF(u.admin, "user", "customer") AS `type`,
			i.fileName AS invoice
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId
			LEFT JOIN invoices AS i ON i.orderId=o.id
			WHERE o.id=?', [$id]
		);

		$order = $order[0];

		$addresses = DB::select('SELECT
			a.*
			FROM addresses AS a
			INNER JOIN orders AS o ON o.deliveryAddressId=a.id OR o.billingAddressId=a.id
			WHERE o.id = ?', [$id]
		);

		$itemsTable = new dataTable();
		$itemsTable->setTitle('Items <span class="string-container small">?</span>');
		$itemsTable->setQuery('SELECT
			p.*,
			ol.quantity,
			p.price * ol.quantity AS total
			FROM products AS p
			INNER JOIN order_lines AS ol ON ol.productId=p.id
			WHERE ol.orderId=?', [$id]
		);
		$itemsTable->addColumn('id', '#');
		$itemsTable->addColumn('title', 'Name', 2);
		$itemsTable->addColumn('quantity', 'Quantity');
		$itemsTable->addColumn('price', 'Price');
		$itemsTable->addColumn('total', 'Subtotal');
		$itemsTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$itemsTable = $itemsTable->render();

    return view('admin/order-profile', compact(
      'sessionUser',
			'order',
			'addresses',
			'itemsTable',
    ));
  }
}
