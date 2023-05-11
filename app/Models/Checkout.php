<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
	use HasFactory;

	protected $table = 'checkout';

	protected $fillable = [
		'userId',
		'deliveryAddressId',
		'billingAddressId',
		'paymentMethodId',
		'total',
		'status',
	];


	public static function createCheckout(int $userId = 0) :int {
		if ($userId == 0) {
			$user = auth()->user();
		} else {
			$user = User::find($userId);
		}

		Self::where('userId', $user->id)->delete();

		$checkout = Self::create([
			'userId' => $user->id,
			'status' => 'addresses',
		]);

		$cartProducts = Cart::where('userId', $user->id)->get();

		foreach ($cartProducts as $i => $product) {
			$checkoutProduct = CheckoutProduct::create([
				'checkoutId' => $checkout->id,
				'productId' => $product->productId,
				'quantity' => $product->quantity,
			]);

			$cartVariants = CartVariants::where('cartId', $product->id)->get();

			foreach ($cartVariants as $i2 => $variant) {
				CheckoutProductVariant::create([
					'checkoutProductId' => $checkoutProduct->id,
					'variantId' => $variant->variantId,
				]);
			}
		}

		Self::calculateTotal($checkout->id);

		return $checkout->id;
	}

	public static function calculateTotal(int $checkoutId) :float {
		$checkoutItems = CheckoutProduct::select('productId')->where('checkoutId', $checkoutId)->get();
		$total = 0;

		foreach ($checkoutItems as $i => $item) {
			$products = Products::select('price')->where('id', $item->productId)->get();

			foreach ($products as $i => $product) {
				$total += $product->price;
			}
		}

		Self::where('id', $checkoutId)->update([
			'total' => $total,
		]);

		return $total;
	}
}
