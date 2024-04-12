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
		if (session()->has('admin-orders-search')) {
			$formValue = session()->get('admin-orders-search')[0];
			$query = session()->get('admin-orders-search')[1];
		
		} else {
			$formValue = [
				'search' => null,
				'type' => 'all',
				'status' => 'all',
			];
			$query = 'SELECT 
				o.*,
				CONCAT(u.firstName, " ", u.lastName) AS `name`
				FROM orders AS o
				INNER JOIN users AS u ON u.id=o.userId
				WHERE o.type != "basket"';
		}

		$searchForm = new DataForm(request(), '/ordersSearch', 'Search');
		$searchForm->addInput('text', 'search', 'Search', $formValue['search'], 255, 0);
		$searchForm->addInput('select', 'type', 'Type', $formValue['type'], null, null, false, null, [], false);
		$searchForm->populateOptions('type', [
			[
				'value' => 'all',
				'label' => '',
			],
			[
				'value' => 'web',
				'label' => 'Web',
			],
			[
				'value' => 'bespoke',
				'label' => 'Beskope',
			],
			[
				'value' => 'interior',
				'label' => 'Interior',
			],
		], false);
		$searchForm->addInput('select', 'status', 'Status', $formValue['status'], null, null, false, null, [], false);
		$searchForm->populateOptions('status', [
			[
				'value' => 'all',
				'label' => '',
			],
			[
				'value' => 'new',
				'label' => 'New',
			],
			[
				'value' => 'processing',
				'label' => 'Processing',
			],
			[
				'value' => 'awaiting-delivery',
				'label' => 'Awaiting Delivery',
			],
			[
				'value' => 'out-for-delivery',
				'label' => 'Out for Delivery',
			],
			[
				'value' => 'complete',
				'label' => 'Complete',
			],
		], false);
		$searchForm = $searchForm->render();

    $ordersTable = new DataTable('orders');
		$ordersTable->setQuery($query, [], 'id', 'DESC');
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('name', 'Name', 3);
		$ordersTable->addColumn('type', 'Type', 2);
		$ordersTable->addColumn('status', 'Status', 2);
		$ordersTable->addColumn('total', 'Total', 2);
		$ordersTable->addColumn('created_at', 'Created', 3 , true);
		$ordersTable->addLinkButton('order-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

    return view('admin/orders', compact(
			'searchForm',
			'ordersTable',
    ));
  }

	public function search(Request $request)
	{
		$request->validate([
			'search' => 'max:255',
		]);

		$query = 'SELECT 
				o.*,
				CONCAT(u.firstName, " ", u.lastName) AS `name`
				FROM orders AS o
				INNER JOIN users AS u ON u.id=o.userId
				WHERE o.type != "basket"';

		if (!empty($request->search)) {
			$query .= sprintf(' AND (o.id = "%1$s" OR u.id = "%1$s" OR CONCAT(u.firstName, " ", u.lastName) LIKE "%%%1$s%%")', $request->search);
			
			$explode = str_split($query);

			foreach ($explode as $i => $character) {
				if ($character == '%') {
					$explode[$i] = '%%';
				}
			}

			$query = implode($explode);
		}

		if ($request->type != 'all') {
			$query .= sprintf(' AND o.type = "%s"', $request->type);
		}

		if ($request->status != 'all') {
			$query .= sprintf(' AND o.status = "%s"', $request->status);
		}

		$values = [
			'search' => $request->search,
			'type' => $request->type,
			'status' => $request->status,
		];
			
		session()->put('admin-orders-search', [$values, $query]);

		return redirect('/admin/orders');
	}
}
