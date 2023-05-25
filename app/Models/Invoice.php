<?php

namespace App\Models;

use DB;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	use HasFactory;

  protected $fillable = [
    'orderId',
    'fileName',
  ];

	public static function createInvoice(int $orderId) {
		$order = Order::find($orderId);

		if ($order == null) {
			return false;
		}

		$order = DB::select('SELECT 
			o.id,
			o.userId,
			o.paymentMethodId,
			CONCAT(u.firstName, " ", u.lastName) AS `name`,
			SUM(ol.quantity) AS `count`,
			SUM(p.price * ol.quantity) AS `total`,
			DATE_FORMAT(o.created_at, "%d/%m/%Y") AS `date`
			FROM orders AS o
			LEFT JOIN order_lines AS ol ON ol.orderId=o.id
			LEFT JOIN products AS p ON p.id=ol.productId
			INNER JOIN users AS u ON u.id=o.userId
			WHERE o.id=?
			GROUP BY o.id
			LIMIT 1',
			[$orderId]
		);

		$order = $order[0];

		$products = DB::select('SELECT
			p.id,
			p.title,
			p.price,
			pi.fileName,
			ol.quantity
			FROM orders AS o
			LEFT JOIN order_lines AS ol ON ol.orderId=o.id
			LEFT JOIN products AS p ON p.id=ol.productId
			LEFT JOIN product_images AS pi ON pi.productId=p.id AND pi.primary=1
			WHERE o.id=?
			GROUP BY p.id',
			[$orderId]
		);

		$billingAddress = DB::select('SELECT
			a.id,
			a.type,
			CONCAT(a.firstName, " ", a.lastName) AS `name`,
			a.company,
			a.line1,
			a.line2,
			a.line3,
			a.city,
			a.region,
			co.name AS country,
			a.postcode,
			a.phone,
			a.email
			FROM orders AS o
			LEFT JOIN addresses AS a ON a.id=o.billingAddressId
			INNER JOIN countries AS co ON co.code=a.country
			WHERE o.id=?
			GROUP BY a.id
			LIMIT 1',
			[$orderId]
		);

		$billingAddress = $billingAddress[0];

		$deliveryAddress = DB::select('SELECT
			a.id,
			a.type,
			CONCAT(a.firstName, " ", a.lastName) AS `name`,
			a.company,
			a.line1,
			a.line2,
			a.line3,
			a.city,
			a.region,
			co.name AS country,
			a.postcode,
			a.phone,
			a.email
			FROM orders AS o
			LEFT JOIN addresses AS a ON a.id=o.deliveryAddressId
			INNER JOIN countries AS co ON co.code=a.country
			WHERE o.id=?
			GROUP BY a.id
			LIMIT 1',
			[$orderId]
		);

		$deliveryAddress = $deliveryAddress[0];

		$user = User::find($order->userId);

		$method = $user->findPaymentMethod($order->paymentMethodId);

		$paymentMethod = [
			'id' => $method->id,
			'brand' => ucfirst($method->card->brand),
			'last4' => $method->card->last4,
			'exp' => $method->card->exp_month . '/' . substr($method->card->exp_year, 2),
			'postcode' => $method->billing_details->address->postal_code,
		];

		$data = [
			'date' => date('d/m/Y'),
			'order' => $order,
			'products' => $products,
			'billingAddress' => $billingAddress,
			'deliveryAddress' => $deliveryAddress,
			'paymentMethod' => $paymentMethod,
		];

		$pdf = Pdf::loadView('templates/invoice', $data);
		$fileName = 'order-invoice-' . $orderId . '-' . $_SERVER['REQUEST_TIME'] . '.pdf';

		uploadS3($fileName, $pdf->download());

		$invoice = Invoice::create([
			'orderId' => $orderId,
			'fileName' => $fileName,
		]);

		return $invoice->id;
	}
}
