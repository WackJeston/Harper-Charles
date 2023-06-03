<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Exceptions\IncompletePayment;

class Order extends Model
{
  use HasFactory;

	protected $fillable = [
		'userId',
		'deliveryAddressId',
		'billingAddressId',
		'paymentMethodId',
		'total',
		'status',
	];

	public static function createOrder(int $userId = 0) {
		if ($userId == 0) {
			$user = auth()->user();
		} else {
			$user = User::find($userId);
		}

		$checkoutId = Checkout::select('id')->where('userId', $user->id)->first();

		if (!$checkoutId) {
			return 0;
		}

		Checkout::calculateTotal($checkoutId->id);
		$checkout = Checkout::where('userId', $user->id)->first();

		$deliveryAddress = Address::find($checkout->deliveryAddressId);

		// TODO: Uncomment for live payments
		
		// $transaction = $user->charge(
		// 	$checkout->total * 100,
		// 	$checkout->paymentMethodId,
		// 	[
		// 		'off_session' => true,
		// 		'customer' => $user->stripe_id,
		// 		'metadata' => [
		// 			'user_id' => $user->id,
		// 			'checkout_id' => $checkout->id,
		// 		],
		// 		'receipt_email' => $user->email,
		// 		'shipping' => [
		// 			'name' => $user->firstName . ' ' . $user->lastName,
		// 			'phone' => $deliveryAddress->phone,
		// 			'address' => [
		// 				'city' => $deliveryAddress->city,
		// 				'country' => $deliveryAddress->country,
		// 				'line1' => $deliveryAddress->line1,
		// 				'line2' => $deliveryAddress->line2,
		// 				'postal_code' => $deliveryAddress->postCode,
		// 				'state' => $deliveryAddress->region,
		// 			],
		// 		]
		// 	]
		// );
		
		$order = Self::create([
			'userId' => $checkout->userId,
			'deliveryAddressId' => $checkout->deliveryAddressId,
			'billingAddressId' => $checkout->billingAddressId,
			'paymentMethodId' => $checkout->paymentMethodId,
			'total' => $checkout->total,
		]);

		$checkoutProducts = CheckoutProduct::where('checkoutId', $checkout->id)->get();

		foreach ($checkoutProducts as $i => $product) {
			$orderLine = OrderLine::create([
				'orderId' => $order->id,
				'productId' => $product->productId,
				'quantity' => $product->quantity,
			]);

			$checkoutVariants = CheckoutProductVariant::where('checkoutProductId', $product->id)->get();

			foreach ($checkoutVariants as $i2 => $variant) {
				OrderLineVariant::create([
					'orderLineId' => $orderLine->id,
					'variantId' => $variant->variantId,
				]);
			}
		}

		return $order->id;
	}

	public static function getLines(int $orderId) {
		$lines = DB::select('SELECT 
			ol.id AS `orderLineId`,
			p.*
			FROM order_lines AS ol
			INNER JOIN products AS p ON p.id=ol.productId
			WHERE ol.orderId=?',
			[$orderId]
		);

		foreach($lines AS $i2 => $line) {
			$line->variants = DB::select('SELECT
				CONCAT(pv2.title, ": ", pv.title) AS `variant`
				FROM order_line_variants AS olv
				INNER JOIN product_variants AS pv ON pv.id=olv.variantId AND pv.show=1
				INNER JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId AND pv2.show=1
				WHERE olv.orderLineId=?',
				[$line->orderLineId]
			);

			$variantsPre = [];

			foreach ($line->variants as $i3 => $variant) {
				$variantsPre[] = $variant->variant;
			}

			$line->variants = $variantsPre;
		}

		return $lines;
	}
}
