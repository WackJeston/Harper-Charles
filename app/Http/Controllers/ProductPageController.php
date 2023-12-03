<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use App\Models\CartVariants;
use App\Models\Products;
use App\Models\ProductImages;
use Illuminate\Http\Request;


class ProductPageController extends Controller
{
  public function show($id)
  {
    $product = Products::find($id);
		$productImages = DB::select(sprintf('SELECT
			pi.id,
			a.fileName,
			a.name
			FROM product_images AS pi
			INNER JOIN asset AS a ON a.id = pi.assetId
			WHERE pi.productId = %d
			ORDER BY pi.primary DESC, pi.id ASC', $id
		));

		$productImages = cacheImages($productImages, 2000, 2000);
		$imageCount = count($productImages);

		if (!empty($productImages)) {
			foreach ($productImages as $i => $image) {
				preloadImage($image->fileName, $i = 0 ? true : false);
			}
		}

    $variantRecords = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
      GROUP_CONCAT(pv2.id, "|", pv2.title ORDER BY pv2.title) AS options
      FROM product_variants AS pv
      INNER JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
      INNER JOIN product_variant_joins AS pvj ON pvj.variantId=pv2.id
      WHERE pv.parentVariantId IS NULL
      AND pvj.productId = "%d"
      AND pv.show = 1
      AND pv2.show = 1
      GROUP BY pv.id
      ORDER BY pv2.title, pv.title
    ', $id));

    $variants = [];

    foreach ($variantRecords as $i => $variant) {
      $optionsPre = explode(',', $variant->options);

      $variants[$i] = [
        $id = $variant->id,
        $title = $variant->title,
      ];

      foreach ($optionsPre as $i2 => $option) {
        $variants[$i][2][$i2] = explode('|', $option);
      }
    }

    return view('public/product-page', compact(
      'product',
      'productImages',
      'imageCount',
      'variants',
    ));
  }


  public function cartAdd($productId, $variantCount, $selectedVariants)
  {
    $sessionUser = auth()->user();
    $response = [];

    if (!auth()->user()) {
      $response['success'] = false;
      return $response;
    }

    $allCartItems = DB::select(sprintf('SELECT
      id,
      quantity
      FROM cart
      WHERE userId=%1$d
      AND productId=%2$d',
      $sessionUser->id,
      $productId
    ));

    if (!empty($allCartItems)) {
      $allCartVariants = DB::select(sprintf('SELECT
        cartId,
        variantId
        FROM cart_variants
        WHERE cartId IN (%1$s)',
        implode(',', array_column($allCartItems, 'id'))
      ));

      if (!empty($allCartVariants)) {
        $allItemRecords = [];

        foreach ($allCartItems as $i => $item) {
          $allItemRecords[$item->id]['quantity'] = $item->quantity;
          $allItemRecords[$item->id]['variants'] = [];
        }

        foreach ($allCartVariants as $i => $variant) {
          $allItemRecords[$variant->cartId]['variants'][$i] = $variant->variantId;
        }

        $selectedVariants = explode(',', $selectedVariants);
        sort($selectedVariants);
        $selectedVariants = implode(',', $selectedVariants);

        foreach ($allItemRecords as $recordId => $record) {
          sort($record['variants']);

          if (implode(',', $record['variants']) == $selectedVariants) {
            Cart::where('id', $recordId)->update([
              'quantity' => $record['quantity'] + 1,
            ]);

            $response['success'] = true;
            return $response;
          }
        }

      } else {
        Cart::where('id', $allCartItems[0]->id)->update([
          'quantity' => $allCartItems[0]->quantity + 1,
        ]);

        $response['success'] = true;
        return $response;
      }
    }

    $cart = Cart::create([
      'userId' => $sessionUser->id,
      'productId' => $productId,
      'quantity' => 1,
    ]);

    if ($variantCount > 0) { 
      $selectedVariants = explode(',', $selectedVariants);

      for ($i=0; $i < $variantCount; $i++) {
        CartVariants::create([
          'cartId' => $cart->id,
          'variantId' => $selectedVariants[$i],
        ]);
      }
    }

    $response['success'] = true;
    return $response;
  }
}
