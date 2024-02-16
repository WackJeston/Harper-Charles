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
		'items',
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

	public static function getOrder(int $orderId) {
		$order = DB::select('SELECT
			o.id,
			o.userId,
			o.paymentMethodId,
			o.status,
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

		if (empty($order)) {
			return false;
		}

		$order = $order[0];

		$order->lines = DB::select('SELECT
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

		$order->billingAddress = DB::select('SELECT
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

		$order->billingAddress = $order->billingAddress[0];

		$order->deliveryAddress = DB::select('SELECT
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

		$order->deliveryAddress = $order->deliveryAddress[0];

		return $order;
	}

	public static function getNotes(int $orderId) {
		$notes = DB::select('SELECT
			o.*,
			DATE_FORMAT(o.created_at, "%d/%m/%Y") AS `date`
			FROM order_notes AS o
			WHERE o.orderId=?
			ORDER BY o.id DESC',
			[$orderId]
		);

		return $notes;
	}
}
