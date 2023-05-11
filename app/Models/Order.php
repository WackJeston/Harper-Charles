<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	public static function createOrder(int $userId = 0) :int {
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

		$transaction = $user->charge(
			$checkout->total * 100,
			$checkout->paymentMethodId,
		);

		dd($transaction);

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

			foreach ($cartVariants as $i2 => $variant) {
				OrderLineVariant::create([
					'orderLineId' => $orderLine->id,
					'variantId' => $variant->variantId,
				]);
			}
		}

		return $order->id;
	}
}
