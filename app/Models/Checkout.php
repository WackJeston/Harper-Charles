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
		'status',
	];


	public static function createCheckout() {
		Self::where('userId', auth()->user()->id)->delete();

		$checkout = Self::create([
			'userId' => auth()->user()->id,
			'status' => 'addresses',
		]);

		$cartProducts = Cart::where('userId', auth()->user()->id)->get();

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
	}

	public static function calculateTotal($checkoutId) {
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
	}
}
