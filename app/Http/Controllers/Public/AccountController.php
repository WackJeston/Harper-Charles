<?php
namespace App\Http\Controllers\Public;

use Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataClasses\DataTable;
use App\DataClasses\DataForm;
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
			DATE_FORMAT(o.ordered_at, "%%d/%%m/%%Y") AS `date`,
			CONCAT(u.firstName, " ", u.lastName) AS `name`
			FROM orders AS o
			INNER JOIN users AS u ON u.id=o.userId
			WHERE o.type != "basket"
			AND o.userId = ?',
			[auth()->user()->id], 
			'id', 
			'DESC'
		);
		$ordersTable->addColumn('id', '#');
		$ordersTable->addColumn('status', 'Status');
		$ordersTable->addColumn('items', 'Items', 1, true);
		$ordersTable->addColumn('total', 'Total', 1, false, 'currency');
		$ordersTable->addColumn('date', 'Date');
		$ordersTable->addLinkButton('account/order/?', 'fa-solid fa-folder-open', 'Order Details');
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
			if ($order->userId != auth()->user()->id) {
				return redirect("/account")->withErrors(['1' => 'Order not found.']);
			}

			$invoice = Invoice::createInvoice($order->id);
			$invoice = cachePdf($invoice->fileName, true);

			$notesForm = new DataForm(request(), sprintf('/account/orderAddNote/%d', $order->id), 'Add');
			$notesForm->addInput('textarea', 'note', 'Note', '', 4000, 1, true);
			$notesForm = $notesForm->render();

			$notesTable = new DataTable('notes');
			$notesTable->setQuery('SELECT 
				o.*,
				u.admin,
				IF(u.id = ?, CONCAT(u.firstName, " ", u.lastName), u.firstName) AS `name`
				FROM order_notes AS o
				INNER JOIN users AS u ON u.id = o.userId
				WHERE o.orderId = ?', 
				[
					auth()->user()->id,
					$orderId
				], 
				'id', 
				'DESC'
			);
			$notesTable->addColumn('id', '#');
			$notesTable->addColumn('name', 'Name', 2);
			$notesTable->addColumn('note', 'Note', 4, false, 'paragraph');
			$notesTable->addColumn('created_at', 'Date', 2, true);
			$notesTable->highlight('userId', auth()->user()->id, false);
			$notesTable = $notesTable->render();

			$itemsTable = new DataTable('items');
			$itemsTable->setQuery('SELECT 
				p.*,
				ol.quantity
				FROM products AS p
				INNER JOIN order_lines AS ol ON ol.productId = p.id
				WHERE ol.orderId = ?',
				[$orderId], 
				'title'
			);
			$itemsTable->setTitle('Items');
			$itemsTable->addColumn('id', '#');
			$itemsTable->addColumn('title', 'Title', 3);
			$itemsTable->addColumn('quantity', 'Qty');
			$itemsTable->addColumn('price', 'Price', 2, false, 'currency');
			$itemsTable->addLinkButton('product/?', 'fa-solid fa-folder-open', 'View Product');
			$itemsTable = $itemsTable->render();

			return view('public/account', compact(
				'action',
				'order',
				'invoice',
				'notesForm',
				'notesTable',
				'itemsTable',
			));

		} else {
			return redirect("/account")->withErrors(['1' => 'Order not found.']);
		}
	}

	public function orderAddNote(Request $request, int $orderId) {
		if ($order = Order::find($orderId)) {
			if ($order->userId != auth()->user()->id) {
				return redirect("/account")->withErrors(['1' => 'Order not found.']);
			}
			
			$request->validate([
				'note' => 'max:4000',
			]);
	
			OrderNote::create([
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
