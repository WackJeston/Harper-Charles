<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Basket;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineVariant;
use App\Models\Products;
use App\Models\ProductVariants;
use App\Models\ProductVariantJoins;
use Illuminate\Http\Request;


class ProductPageController extends PublicController
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

			$productImages = cacheImages($productImages, 1800, 1800);
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
				INNER JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
				INNER JOIN product_variant_joins AS pvj ON pvj.variantId=pv2.id
				WHERE pvj.productId = ?
				AND pv.parentVariantId IS NULL
				GROUP BY pv.id',
				[$id]
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

			$optionsRecords = cacheImages($optionsRecords, 600, 600);

			$variants = [];

			foreach ($variantRecords as $i => $variant) {
				$variants[$variant->id] = [
					'id' => $variant->id,
					'title' => $variant->title,
					'type' => $variant->type,
					'selected' => null,
					'done' => false,
					'options' => [],
				];
			}

			foreach ($optionsRecords as $i => $option) {
				if ($variants[$option->parent]['selected'] == null) {
					$variants[$option->parent]['selected'] = $option->id;
				}
				
				$variants[$option->parent]['options'][$option->id] = [
					'id' => $option->id,
					'title' => $option->title,
					'type' => $option->type,
					'fileName' => $option->fileName,
					'colour' => $option->colour,
				];
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

			if ($product->orbitalVisionId != null) {
				$scripts = [
					[
						'path' => '/js/orbital-vision/viewer.js',
						'loadType' => 'defer',
						'onLoad' => '',
					],
					[
						'path' => '/js/orbital-vision/app.js',
						'loadType' => 'defer',
						'onLoad' => sprintf('orbitalVistionLoad3dModel("%s", %d)', env('ORBITAL_VISION_API_KEY'), $product->orbitalVisionId),
					],
				];
		
				$stylesheets = [
					'/css/orbital-vision/app.css',
				];

			} else {
				$scripts = [];
				$stylesheets = [];
			}
			
			$records = cacheRecords('public-page-product-' . $id, [
				'product' => $product,
				'productImages' => $productImages,
				'imageCount' => $imageCount,
				'variants' => $variants,
				'specs' => $specs,
				'scripts' => $scripts,
				'stylesheets' => $stylesheets,
			]);
		}

		$product = $records['product'];
		$productImages = $records['productImages'];
		$imageCount = $records['imageCount'];
		$variants = $records['variants'];
		$specs = $records['specs'];
		$scripts = $records['scripts'];
		$stylesheets = $records['stylesheets'];

		return view('public/product-page', compact(
			'product',
			'productImages',
			'imageCount',
			'variants',
			'specs',
			'scripts',
			'stylesheets'
		));
  }


  public function basketAdd(Request $request)
  {
		if (!$product = Products::find($request->productId)) {
			return redirect('/shop')->withErrors(['1' => 'Product not found']);
		}

		if ($product->active != 1) {
			return redirect('/shop')->withErrors(['1' => 'Product not currently available']);
		}

		if (!auth()->user()) {
			return redirect("/login")->withErrors(['1' => 'Please login before adding items to your basket.']);
		}

		if (!empty($request->configuration)) {
			$configuration = json_decode($request->configuration);

			foreach ($configuration->configured_products as $i => $productData) {
				foreach ($productData->attributes as $i2 => $variantData) {
					$variantTitle = trim($variantData->attribute_name, ':');
					$variantTitle = trim($variantTitle, 's');

					$variant = ProductVariants::where('parentVariantId', null)->where('title', $variantTitle)->first();

					if ($variant == null) {
						$variant = new ProductVariants;
						$variant->title = $variantTitle;
						$variant->type = 'text';
						$variant->reference = 'orbital-vision';
						$variant->save();
					}

					$optionTitle = $variantData->attribute_value_name;

					$option = ProductVariants::where('parentVariantId', $variant->id)->where('title', $optionTitle)->first();

					if ($option == null) {
						$option = new ProductVariants;
						$option->parentVariantId = $variant->id;
						$option->title = $optionTitle;
						$option->type = 'text';
						$option->reference = 'orbital-vision';
						$option->save();
					}

					$configuration->configured_products[$i]->attributes[$i2]->custom_id = $option->id;
				}
			}
		}

		if (!$order = Order::where('userId', auth()->user()->id)->where('status', 'basket')->first()) {
			$order = new Order;
			$order->userId = auth()->user()->id;
			$order->status = 'basket';
			$order->save();
		}

		$matchingOrders = DB::select('SELECT
			ol.id
			FROM order_lines AS ol
			INNER JOIN orders AS o ON o.id = ol.orderId
			WHERE ol.orderId = ?
			AND ol.productId = ?
			AND o.status = "basket"
			GROUP BY ol.id',
			[$order->id, $product->id]
		);

		if (!empty($matchingOrders)) {
			$variantsIds = [];

			foreach ($request->all() as $i => $variantId) {
				if (str_contains($i, 'variant')) {
					$variantsIds[] = $variantId;
				}
			}

			if (!empty($request->configuration)) {
				foreach ($configuration->configured_products as $i => $productData) {
					foreach ($productData->attributes as $key => $variantData) {
						$variantsIds[] = $variantData->custom_id;
					}
				}
			}

			sort($variantsIds);
			$variantsString = implode('-', $variantsIds);

			foreach ($matchingOrders as $i => $matchingOrder) {
				$matchingOrderVariants = DB::select('SELECT
					olv.variantId
					FROM order_line_variants AS olv
					WHERE olv.orderLineId = ?
					GROUP BY olv.id',
					[$matchingOrder->id]
				);

				$matchingOrderVariantsIds = [];

				foreach ($matchingOrderVariants as $i => $matchingOrderVariant) {
					$matchingOrderVariantsIds[] = $matchingOrderVariant->variantId;
				}

				sort($matchingOrderVariantsIds);
				$matchingOrderVariantsString = implode('-', $matchingOrderVariantsIds);

				if ($matchingOrderVariantsString == $variantsString) {
					$orderLine = OrderLine::find($matchingOrder->id);
					$orderLine->quantity += $request->quantity;
					$orderLine->save();

					return redirect('/product/' . $product->id)->with('message', sprintf('Product #%d Added to basket.', $product->id));
				}
			}
		}

		$orderLine = new OrderLine;
		$orderLine->orderId = $order->id;
		$orderLine->productId = $product->id;
		$orderLine->quantity = $request->quantity;

		if (empty($request->configuration)) {
			$orderLine->price = $product->price;
		}

		$orderLine->save();

		foreach ($request->all() as $i => $variantId) {
			if (str_contains($i, 'variant')) {
				$orderLineVariant = new OrderLineVariant;
				$orderLineVariant->orderLineId = $orderLine->id;
				$orderLineVariant->variantId = $variantId;
				$orderLineVariant->save();
			}
		}

		if (!empty($request->configuration)) {
			foreach ($configuration->configured_products as $i => $productData) {
				$fileName = sprintf('orbital-vision-order-line-image-%s.%s',
					$orderLine->id,
					'jpg'
				);

				$orderLine->assetId = storeImageFromString($fileName, $productData->thumbnail);
				$orderLine->price = $productData->price->base;
				$orderLine->save();

				foreach ($productData->attributes as $key => $variantData) {
					$orderLineVariant = new OrderLineVariant;
					$orderLineVariant->orderLineId = $orderLine->id;
					$orderLineVariant->variantId = $variantData->custom_id;
					$orderLineVariant->save();
				}
			}
		}

		$order->items = DB::select('SELECT
			SUM(ol.quantity) AS items
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			GROUP BY ol.orderId
			LIMIT 1',
			[$order->id]
		)[0]->items;

		$order->total = DB::select('SELECT
			SUM(ol.quantity * ol.price) AS total
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			GROUP BY ol.orderId
			LIMIT 1',
			[$order->id]
		)[0]->total;

		$order->save();

		return redirect('/product/' . $product->id)->with('message', sprintf('Product #%d Added to basket.', $product->id));
  }
}
