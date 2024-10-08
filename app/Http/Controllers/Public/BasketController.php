<?php
namespace App\Http\Controllers\Public;

use DB;
use PDO;
use App\Models\Products;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\OrderLineVariant;
use App\Models\OrderNote;
use Illuminate\Http\Request;

class BasketController extends PublicController
{
  public function show() {
		$basket = DB::select('SELECT
			o.*
			FROM orders AS o
			WHERE o.userId = ?
			AND o.type = "basket"
			LIMIT 1',
			[auth()->user()->id]
		);

		if (!empty($basket)) {
			$basket = $basket[0];

			$orderNote = OrderNote::where('orderId', $basket->id)->where('primary', 1)->first();

			if (!empty($orderNote)) {
				$orderNote->delete();
			}

			if ($basket->status != 'basket') {
				$order = Order::where('userId', auth()->user()->id)->where('type', 'basket')->first();
				$order->status = 'basket';
				$order->save();
			}

			$basket->lines = DB::select('SELECT
				ol.*,
				p.id AS productId,
				p.title,
				p.subtitle,
				p.maxQuantity AS max,
				p.stock,
				IF(isnull(ol.assetId), a.fileName, a2.fileName) AS fileName
				FROM order_lines AS ol
				INNER JOIN products AS p ON p.id = ol.productId
				LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
				LEFT JOIN asset AS a ON a.id = pi.assetId
				LEFT JOIN asset AS a2 ON a2.id = ol.assetId
				WHERE ol.orderId = ?
				GROUP BY ol.id
				ORDER BY ol.created_at ASC',
				[$basket->id]
			);

			foreach ($basket->lines as $i => $line) {
				if (!is_null($line->stock) && (is_null($line->max) || $line->stock < $line->max)) {
					$line->max = $line->stock;
				}
			}

			$basket->lines = cacheImages($basket->lines, 600, 600, true, 'EFEFEF');

			foreach ($basket->lines as $i => $line) {
				$basket->lines[$i]->variants = DB::select('SELECT
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

    return view('public/basket', compact(
      'basket',
    ));
  }

  public function basketRemove(int $id) {
		$orderLine = OrderLine::find($id);
		$orderId = $orderLine->orderId;
		$orderLine->delete();

		self::setTotal($orderId);

		return true;
  }

  public function quantityUpdate(int $id, int $quantity) {
		$return = true;
		$orderLine = OrderLine::find($id);
		
		if (!$product = Products::find($orderLine->productId)) {
			$orderLine->delete();

			$return = false;
		}

		if (!$product->available()) {
			$orderLine->delete();

			$return = false;
		}

		$orderLine->quantity = $quantity;
		$orderLine->total = $orderLine->price * $quantity;
		$orderLine->save();

		self::setTotal($orderLine->orderId);

		return $return;
  }

	public function setTotal(int $id) {
		$order = Order::find($id);

		$order->items = DB::select('SELECT
			COUNT(ol.id) AS items
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			LIMIT 1',
			[$order->id]
		)[0]->items;

		$order->total = DB::select('SELECT
			SUM(ol.total) AS total
			FROM order_lines AS ol
			WHERE ol.orderId = ?
			LIMIT 1',
			[$order->id]
		)[0]->total;

		$order->save();
	}
}
