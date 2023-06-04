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
		$order = Order::getOrder($orderId);

		if ($order == false) {
			return false;
		}

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
