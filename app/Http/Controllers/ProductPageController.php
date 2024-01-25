<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cart;
use App\Models\CartVariants;
use App\Models\Products;
use Illuminate\Http\Request;


class ProductPageController extends Controller
{
  public function show($id)
  {
		if (!$product = Products::find($id)) {
      return redirect('/shop')->withErrors(['1' => 'Product not found']);
    }

		if ($product->active != 1) {
			return redirect('/shop')->withErrors(['1' => 'Product not currently available']);
		}

		if (!$records = getCachedRecords('public-page-product-' . $id)) {
			$product = Products::find($id);
			$productImages = DB::select('SELECT
				pi.id,
				a.fileName,
				a.name
				FROM product_images AS pi
				INNER JOIN asset AS a ON a.id = pi.assetId
				WHERE pi.productId = ?
				AND pi.active = 1
				ORDER BY pi.sequence ASC, pi.id ASC', 
				[$id]
			);

			$productImages = cacheImages($productImages, 2000, 2000);
			$imageCount = count($productImages);

			if (!empty($productImages)) {
				foreach ($productImages as $i => $image) {
					preloadImage($image->fileName, $i = 0 ? true : false);
				}
			}

			$variantRecords = DB::select('SELECT
				pv.id,
				pv.title,
				pv.type
				FROM product_variants AS pv
				WHERE pv.parentVariantId IS NULL'
			);

			$optionsRecords = DB::select('SELECT
				pv.*,
				a.fileName,
				pv2.id AS parent
				FROM product_variants AS pv
				INNER JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId
				INNER JOIN product_variant_joins AS pvj ON pvj.variantId=pv.id
				LEFT JOIN asset AS a ON a.id=pv.assetId
				WHERE pv.parentVariantId IS NOT NULL
				AND pvj.productId = ?
				AND pv.active = 1
				AND pv2.active = 1
				GROUP BY pv.id',
				[$id]
			);

			$optionsRecords = cacheImages($optionsRecords, 800, 800);

			$variants = [];

			foreach ($variantRecords as $i => $variant) {
				$variants[$variant->id] = [
					'id' => $variant->id,
					'title' => $variant->title,
					'type' => $variant->type,
					'options' => [],
				];
			}

			foreach ($optionsRecords as $i => $option) {
				$variants[$option->parent]['options'][$option->id] = [
					'id' => $option->id,
					'title' => $option->title,
					'type' => $option->type,
					'fileName' => $option->fileName,
					'colour' => $option->colour,
				];
			}

			foreach ($variants as $i => $variant) {
				if(empty($variant['options'])) {
					unset($variants[$i]);
				}
			}

			$specs = DB::select('SELECT
				ps.label,
				ps.value
				FROM product_spec AS ps
				WHERE ps.productId = ?
				AND ps.active = 1
				ORDER BY ps.sequence ASC',
				[$id]
			);
			
			$records = cacheRecords('public-page-product-' . $id, [
				'product' => $product,
				'productImages' => $productImages,
				'imageCount' => $imageCount,
				'variants' => $variants,
				'specs' => $specs,
			]);
		}

		$product = $records['product'];
		$productImages = $records['productImages'];
		$imageCount = $records['imageCount'];
		$variants = $records['variants'];
		$specs = $records['specs'];

		return view('public/product-page', compact(
			'product',
			'productImages',
			'imageCount',
			'variants',
			'specs'
		));
  }


  public function cartAdd($productId, $variantCount, $selectedVariants)
  {
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
