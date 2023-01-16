<?php

namespace App\Http\Controllers;

use DB;
use PDO;
use App\Models\Cart;
use App\Models\CartVariants;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function show()
  {
    if (!auth()->user()) {
      return redirect("/login")->with('message', 'Please login before viewing your cart.');
    }

    $sessionUser = auth()->user();

    $cartItems = DB::select(sprintf('SELECT
      c.id,
      p.id AS productId,
      p.title,
      p.subtitle,
      p.price,
      c.quantity,
      pi.filename,
      GROUP_CONCAT(pv2.title, ": ", pv.title) AS variants
      FROM cart AS c
      INNER JOIN products AS p ON p.id=c.productId
      LEFT JOIN product_images AS pi ON pi.productId=p.id AND pi.primary=1
      LEFT JOIN cart_variants AS cv ON cv.cartId=c.id
      LEFT JOIN product_variants AS pv ON pv.id=cv.variantId
      LEFT JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId
      WHERE c.userId = %d
      GROUP BY c.id
      ORDER BY c.created_at', $sessionUser->id
    ));

    $variants = [];

    foreach ($cartItems as $i => $item) {
      foreach (explode(',', $item->variants) as $i2 => $variant) {
        if ($variant != '') {
          $variants[$item->id][] = $variant;
        }
      }
    }

    return view('public/cart', compact(
      'sessionUser',
      'cartItems',
      'variants',
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
