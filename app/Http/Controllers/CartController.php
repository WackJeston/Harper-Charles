<?php

namespace App\Http\Controllers;

use DB;
use PDO;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function show()
  {
    if (!auth()->user()) {
      return redirect("/login")->withErrors(['1' => 'Please login before viewing your cart.']);
    }

		$cart = DB::select('SELECT
			o.*
			FROM orders AS o
			WHERE o.userId = ?
			AND o.status = "cart"
			LIMIT 1',
			[auth()->user()->id]
		);

		if (!empty($cart)) {
			$cart = $cart[0];

			$cart->lines = DB::select('SELECT
				ol.*,
				p.id AS productId,
				p.title,
				p.subtitle,
				p.price,
				a.filename
				FROM order_lines AS ol
				INNER JOIN products AS p ON p.id = ol.productId
				LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
				LEFT JOIN asset AS a ON a.id = pi.assetId
				WHERE ol.orderId = ?
				GROUP BY ol.id
				ORDER BY ol.created_at ASC',
				[$cart->id]
			);

			foreach ($cart->lines as $i => $line) {
				$cart->lines[$i]->variants = DB::select('SELECT
					olv.*,
					pv.id AS variantId,
					pv.title,
					pv.type,
					a.fileName,
					pv.colour,
					pv2.id AS parentVariantId,
					pv2.title AS parentTitle
					FROM order_line_variants AS olv
					INNER JOIN product_variants AS pv ON pv.id = olv.variantId
					INNER JOIN product_variants AS pv2 ON pv2.id = pv.parentVariantId
					LEFT JOIN asset AS a ON a.id = pv.assetId
					WHERE olv.orderLineId = ?
					GROUP BY olv.id',
					[$line->id]
				);
			}
		}

		dd($cart);

    // $variants = [];

    // foreach ($cartItems as $i => $item) {
    //   foreach (explode(',', $item->variants) as $i2 => $variant) {
    //     if ($variant != '') {
    //       $variants[$item->id][] = $variant;
    //     }
    //   }
    // }

    return view('public/cart', compact(
      'cart',
    ));
  }

  public function quantityUpdate($item, $quantity)
  {
    Cart::where('id', $item)->update([
      'quantity' => $quantity,
    ]);
  }

  public function cartRemove($item)
  {
    Cart::where('id', $item)->delete();
    CartVariants::where('cartId', $item)->delete();
  }
}
