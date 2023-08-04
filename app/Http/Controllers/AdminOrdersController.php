<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\dataTable;
use App\dataForm;
use App\Models\Orders;


class AdminOrdersController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();
		
		$ordersTable = new DataTable();
		$ordersTable->setQuery('SELECT 
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `user`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId'
		);
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('user', 'User', 2);
		$ordersTable->addColumn('status', 'Status');
		$ordersTable->addColumn('total', 'Total');
		$ordersTable->addColumn('created_at', 'Created', 2 , true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

    return view('admin/orders', compact(
      'sessionUser',
			'ordersTable',
    ));
  }
}
