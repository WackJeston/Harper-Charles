<?php

namespace App\Http\Controllers;

use DB;
use App\DataTable;


class AdminDashboardController extends Controller
{
  public function show()
  {
		// PAGE COLUMN
		$ordersTable = new DataTable('orders');
		$ordersTable->setQuery('SELECT 
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `user`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId',
			[], 
			'id', 
			'DESC'
		);
		$ordersTable->setTitle('New Orders');
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('user', 'User', 2);
		$ordersTable->addColumn('status', 'Status');
		$ordersTable->addColumn('total', 'Total');
		$ordersTable->addColumn('created_at', 'Created', 2 , true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

		$enquiriesTable = new DataTable('enquiry');
		$enquiriesTable->setQuery('SELECT * FROM enquiry', [], 'id', 'DESC');
		$enquiriesTable->setTitle('New Enquiries');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('type', 'Type', 2);
		$enquiriesTable->addColumn('name', 'Name', 2, true);
		$enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 3);
		$enquiriesTable->addColumn('created_at', 'Date', 2, true);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

		return view('admin/dashboard', compact(
			'ordersTable',
      'enquiriesTable',
    ));
  }
}
