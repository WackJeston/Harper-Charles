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
    'assetId',
  ];

	public static function createInvoice(int $orderId) {
		$order = Order::getOrder($orderId);

		if ($order == false) {
			return false;
		}

		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		
		$method = \Stripe\PaymentMethod::retrieve($order->paymentMethodId);

		$paymentMethod = [
			'id' => $method->id,
			'type' => $method->type,
		];

		switch ($method->type) {
			case 'card':
				$paymentMethod['brand'] = ucfirst($method->card->brand);
				$paymentMethod['last4'] = $method->card->last4;
				$paymentMethod['exp'] = $method->card->exp_month . '/' . substr($method->card->exp_year, 2);
				$paymentMethod['postcode'] = $method->billing_details->address->postal_code;
				break;

			case 'paypal':
				$paymentMethod['email'] = $method->paypal->payer_email;
				break;
		}

		$data = [
			'date' => date('d/m/Y'),
			'order' => $order,
			'paymentMethod' => $paymentMethod,
		];

		$pdf = Pdf::loadView('templates/invoice', $data);
		$fileName = 'order-invoice-' . $orderId . '.pdf';

		Storage::put('pdfs/' . $fileName,  $pdf->download());

		$asset = Asset::create([
			'fileName' => $fileName,
			'fileNameAWS' => $fileName,
		]);

		$invoice = Invoice::create([
			'orderId' => $orderId,
			'assetId' => $asset->id,
		]);

		$result = DB::select('SELECT 
			a.fileName
			FROM invoices AS i
			INNER JOIN asset AS a ON a.id = i.assetId
			WHERE i.orderId = ?', 
			[$order->id]
		)[0];

		return $result;
	}
}
