<?php
namespace App\Http\Controllers\Admin;

use DB;
use App\DataClasses\DataTable;


class DashboardController extends AdminController
{
  public function show()
  {
		// PAGE COLUMN
		$ordersTable = new DataTable('orders');
		$ordersTable->setQuery('SELECT 
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `user`,
			DATE_FORMAT(o.ordered_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId
			WHERE o.status="new"
			AND o.type != "basket"',
			[], 
			'id', 
			'DESC'
		);
		$ordersTable->setTitle('New Orders');
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('user', 'User', 2);
		$ordersTable->addColumn('type', 'Type', 2);
		$ordersTable->addColumn('total', 'Total', 2);
		$ordersTable->addColumn('date', 'Date', 3 , true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

		$enquiriesTable = new DataTable('enquiry');
		$enquiriesTable->setQuery('SELECT 
			*,
			DATE_FORMAT(created_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS `date`
			FROM enquiry', 
			[], 
			'id', 
			'DESC'
		);
		$enquiriesTable->setTitle('New Enquiries');
		$enquiriesTable->addColumn('id', '#');
		$enquiriesTable->addColumn('type', 'Type', 2);
		$enquiriesTable->addColumn('name', 'Name', 2, true);
		// $enquiriesTable->addColumn('email', 'Email', 3);
		$enquiriesTable->addColumn('subject', 'Subject', 3);
		$enquiriesTable->addColumn('date', 'Date', 3, true);
		$enquiriesTable->addLinkButton('enquiry-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$enquiriesTable = $enquiriesTable->render();

		return view('admin/dashboard', compact(
			'ordersTable',
      'enquiriesTable',
    ));
  }
}
