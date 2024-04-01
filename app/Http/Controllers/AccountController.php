<?php

namespace App\Http\Controllers;

use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderNote;
use App\Models\Invoice;
use App\Models\Address;


class AccountController extends PublicController
{
  public function show() {
		$action = 'account';

		$orders = User::getOrders(auth()->user()->id, 'web');

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
		$ordersTable->addColumn('status', 'Status', 2);
		$ordersTable->addColumn('items', 'Items', 2, true);
		$ordersTable->addColumn('total', 'Total', 2, false, 'currency');
		$ordersTable->addColumn('created_at', 'Date', 3);
		$ordersTable->addLinkButton('account/order/?', 'fa-solid fa-folder-open', 'Open Record');
		$ordersTable = $ordersTable->render();

    return view('public/account', compact(
			'action',
			'orders',
			'ordersTable',
    ));
  }

  public function update(Request $request, int $id) {
    $request->validate([
      'firstname' => 'max:100',
      'lastname' => 'max:100',
      'email' => ['email', 'max:100', Rule::unique('users')->ignore($id)],
      'password' => 'nullable|min:6|max:100'
    ]);

    User::where('id', $id)->update([
      'firstname' => $request->firstname,
      'lastname' => $request->lastname,
      'email' => $request->email,
    ]);

    if ($request->password) {
      User::where('id', $id)->update([
        'password' => Hash::make($request->password),
      ]);
    }

    return redirect("/account")->with('message', 'User updated.');
  }

	public function orderShow(int $orderId) {
		$action = 'order';

		if ($order = Order::getOrder($orderId)) {
			$invoice = DB::select('SELECT 
				a.fileName
				FROM invoices AS i
				INNER JOIN asset AS a ON a.id = i.assetId
				WHERE i.orderId = ?', 
				[$orderId]
			);
			
			if (empty($invoice)) {
				$invoice = Invoice::createInvoice($order->id);
			} else {
				$invoice = $invoice[0];
			}
	
			$invoice = cachePdf($invoice->fileName);

			$notesTable = new DataTable('notes');
			$notesTable->setQuery('SELECT 
				o.*,
				CONCAT(u.firstName, " ", u.lastName) AS `name`
				FROM order_notes AS o
				INNER JOIN users AS u ON u.id = o.userId
				WHERE o.orderId = ?', 
				[$orderId], 
				'id', 
				'DESC'
			);
			$notesTable->addColumn('id', '#');
			$notesTable->addColumn('name', 'Name', 2);
			$notesTable->addColumn('note', 'Note', 4, false, 'paragraph');
			$notesTable->addColumn('created_at', 'Date', 3, true);
			$notesTable = $notesTable->render();

			$itemsTable = new DataTable('items');
			$itemsTable->setQuery('SELECT 
				p.*,
				ol.quantity,
				ol.created_at AS `date`
				FROM products AS p
				INNER JOIN order_lines AS ol ON ol.productId = p.id
				WHERE ol.orderId = ?',
				[$orderId], 
				'title'
			);
			$itemsTable->setTitle('Items');
			$itemsTable->addColumn('id', '#');
			$itemsTable->addColumn('title', 'Title', 2);
			$itemsTable->addColumn('quantity', 'Qty', 2, true);
			$itemsTable->addColumn('date', 'Date', 3);
			$itemsTable->addLinkButton('product/?', 'fa-solid fa-folder-open', 'Open Record');
			$itemsTable = $itemsTable->render();

			return view('public/account', compact(
				'action',
				'order',
				'invoice',
				'notesTable',
				'itemsTable',
			));

		} else {
			return redirect("/account")->withErrors(['1' => 'Order not found.']);
		}
	}

	public function orderAddNote(Request $request, int $orderId) {
		if ($order = Order::find($orderId)) {
			$request->validate([
				'note' => 'max:4000',
			]);
	
			OrderNote::create([
				'admin' => 0,
				'orderId' => $orderId,
				'userId' => auth()->user()->id,
				'note' => $request->note,
			]);
	
			return redirect("/account/order/" . $orderId)->with('message', 'Note added.');

		} else {
			return redirect("/account")->withErrors(['1' => 'Order not found.']);
		}

		
	}
}
