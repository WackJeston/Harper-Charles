<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Order;
use App\Models\OrderNote;
use App\Models\Invoice;


class AdminOrderProfileController extends AdminController
{
  public function show($id)
  {
		if (Order::find($id) == null) {
      return redirect('/admin/orders')->withErrors(['1' => 'Order not found']);
    }
		
		$order = DB::select('SELECT 
			o.*,
			u.id AS userId,
			CONCAT(u.firstName, " ", u.lastName) AS `user`,
			IF(u.admin, "user", "customer") AS `contactType`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId
			WHERE o.id=?', [$id]
		);

		$order = $order[0];

		$invoice = Invoice::createInvoice($order->id);
		$order->invoice = cachePdf($invoice->fileName, true);

		$order->primaryNote = OrderNote::select('note')->where('orderId', $order->id)->where('primary', 1)->first();

		if (!empty($order->primaryNote)) {
			$order->primaryNote = $order->primaryNote->note;
		}

		$statuses = Order::getStatuses();
		$statusCheck = false;

		$notesForm = new DataForm(request(), sprintf('/order-profileAddNote/%d', $order->id), 'Add');
		$notesForm->addInput('textarea', 'note', 'Note', '', 4000, 1, true);
		$notesForm = $notesForm->render();

		$notesTable = new DataTable('notes');
		$notesTable->setQuery('SELECT 
			o.*,
			u.admin,
			CONCAT(u.firstName, " ", u.lastName) AS `name`
			FROM order_notes AS o
			INNER JOIN users AS u ON u.id = o.userId
			WHERE o.orderId = ?', 
			[$order->id], 
			'id', 
			'DESC'
		);
		$notesTable->addColumn('id', '#');
		$notesTable->addColumn('name', 'Name', 2);
		$notesTable->addColumn('note', 'Note', 4, false, 'paragraph');
		$notesTable->addColumn('created_at', 'Date', 2, true);
		$notesTable->highlight('userId', $order->userId, false);
		$notesTable = $notesTable->render();

		$itemsTable = new dataTable('items');
		$itemsTable->setTitle('Items <span class="string-container small">?</span>');
		$itemsTable->setQuery('SELECT
			p.*,
			ol.quantity,
			p.price * ol.quantity AS total
			FROM products AS p
			INNER JOIN order_lines AS ol ON ol.productId=p.id
			WHERE ol.orderId = ?', 
			[$id]
		);
		$itemsTable->addColumn('id', '#');
		$itemsTable->addColumn('title', 'Name', 3);
		$itemsTable->addColumn('quantity', 'Quantity', 2);
		$itemsTable->addColumn('price', 'Price', 2);
		$itemsTable->addColumn('total', 'Subtotal', 2, true);
		$itemsTable->addLinkButton('product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$itemsTable = $itemsTable->render();

		$transactionsTable = new dataTable('payments', 'stripeReference');
		$transactionsTable->setTitle('Transactions <span class="string-container small">?</span>');
		$transactionsTable->setQuery('SELECT
			p.*
			FROM payments AS p
			WHERE p.orderId = ?', 
			[$id]
		);
		$transactionsTable->addColumn('id', '#');
		$transactionsTable->addColumn('type', 'Type');
		$transactionsTable->addColumn('status', 'Status', 2);
		$transactionsTable->addColumn('detail', 'Detail', 3, true, 'paragraph');
		$transactionsTable->addColumn('method', 'Method', 1, true);
		$transactionsTable->addColumn('amount', 'Amount');
		$transactionsTable->addColumn('captured', 'Captured', 1, true);
		$transactionsTable->addColumn('created_at', 'Created At', 2, true);
		$transactionsTable->addLinkButton('https://dashboard.stripe.com/payments/?', 'fa-solid fa-folder-open', 'Open Record', false);
		$transactionsTable = $transactionsTable->render();

    return view('admin/order-profile', compact(
			'order',
			'statuses',
			'statusCheck',
			'notesForm',
			'notesTable',
			'itemsTable',
			'transactionsTable',
    ));
  }

	public function proceed($id)
	{
		$order = Order::find($id);

		$statuses = Order::getStatuses();
		$statusCheck = false;

		foreach ($statuses as $i => $status) {
			if ($statusCheck) {
				$order->status = $status;
				$order->save();
				
				return redirect()->back()->with('success', sprintf('Order proceeded to %s.', $status));
			}

			if ($order->status == $status) {
				$statusCheck = true;
			}
		}


		return redirect()->back()->withErrors(['error' => 'Unable to proceed with order.']);
	}

	public function addNote($id)
	{
		$this->validate(request(), [
			'note' => 'required|string|max:4000',
		]);

		OrderNote::create([
			'orderId' => $id,
			'userId' => auth()->user()->id,
			'note' => request('note'),
		]);

		return redirect()->back()->with('success', 'Note added.');
	}
}
