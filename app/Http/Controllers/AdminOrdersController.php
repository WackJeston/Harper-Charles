<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Orders;


class AdminOrdersController extends AdminController
{
  public function show()
  {
    $ordersTable = new DataTable('orders');
		$ordersTable->setQuery('SELECT 
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `name`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId
			WHERE o.type != "basket"',
			[], 
			'id', 
			'DESC'
		);
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('name', 'Name', 3);
		$ordersTable->addColumn('type', 'Type', 2);
		$ordersTable->addColumn('status', 'Status', 2);
		$ordersTable->addColumn('total', 'Total', 2);
		$ordersTable->addColumn('created_at', 'Created', 3 , true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

    return view('admin/orders', compact(
			'ordersTable',
    ));
  }
}
