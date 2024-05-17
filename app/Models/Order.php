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
		'type',
		'status',
		'stripeIntentId',
		'stripeReceipt',
		'ordered_at',
	 	'billingFirstName',
   	'billingLastName',
   	'billingCompany',
   	'billingLine1',
   	'billingLine2',
   	'billingLine3',
   	'billingCity',
   	'billingRegion',
   	'billingCountry',
   	'billingPostCode',
   	'billingPhone',
   	'billingEmail',
	 	'deliveryFirstName',
   	'deliveryLastName',
   	'deliveryCompany',
   	'deliveryLine1',
   	'deliveryLine2',
   	'deliveryLine3',
   	'deliveryCity',
   	'deliveryRegion',
   	'deliveryCountry',
   	'deliveryPostCode',
   	'deliveryPhone',
   	'deliveryEmail',
	];

	public static function getOrder(int $orderId) {
		$order = DB::select('SELECT
			o.*,
			CONCAT(u.firstName, " ", u.lastName) AS `name`,
			SUM(ol.quantity) AS `count`,
			DATE_FORMAT(o.ordered_at, "%d/%m/%Y") AS `date`
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
			ol.*,
			p.id AS productId,
			p.title,
			p.subtitle,
			IF(isnull(ol.assetId), a.fileName, a2.fileName) AS fileName
			FROM order_lines AS ol
			INNER JOIN products AS p ON p.id = ol.productId
			LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
			LEFT JOIN asset AS a ON a.id = pi.assetId
			LEFT JOIN asset AS a2 ON a2.id = ol.assetId
			WHERE ol.orderId = ?
			GROUP BY ol.id
			ORDER BY ol.created_at ASC',
			[$orderId]
		);

		$order->lines = cacheImages($order->lines, 600, 600, true,	'billingEFEFEF');

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

	public static function getStatuses(bool $format = false) {
		if (!$format) {
			return [
				'new' => 'New',
				'processing' => 'Processing',
				'awaiting-despatch' => 'Awaiting Despatch',
				'out-for-delivery' => 'Out For Delivery',
				'complete' => 'Complete',
			];
		
		} else {
			return [
				[
					'value' => 'all',
					'label' => '',
				],
				[
					'value' => 'New',
					'label' => 'New',
				],
				[
					'value' => 'Processing',
					'label' => 'Processing',
				],
				[
					'value' => 'Awaiting Despatch',
					'label' => 'Awaiting Despatch',
				],
				[
					'value' => 'Out for Delivery',
					'label' => 'Out for Delivery',
				],
				[
					'value' => 'Complete',
					'label' => 'Complete',
				],
			];
		}
	}

	public static function getTypes(bool $format = false) {
		if (!$format) {
			return [
				'web' => 'Web',
				'bespoke' => 'Bespoke',
				'interior' => 'Interior',
			];
		
		} else {
			return [
				[
					'value' => 'all',
					'label' => '',
				],
				[
					'value' => 'web',
					'label' => 'Web',
				],
				[
					'value' => 'bespoke',
					'label' => 'Beskope',
				],
				[
					'value' => 'interior',
					'label' => 'Interior',
				],
			];
		}
	}

	public function hasAddresses():bool {
		if (empty($this->billingFirstName) || empty($this->billingLastName) || empty($this->billingLine1) || empty($this->billingCity) || empty($this->billingCountry) || empty($this->billingPostCode) || empty($this->billingPhone) || empty($this->billingEmail)) {
			return false;
		}

		if (empty($this->deliveryFirstName) || empty($this->deliveryLastName) || empty($this->deliveryLine1) || empty($this->deliveryCity) || empty($this->deliveryCountry) || empty($this->deliveryPostCode) || empty($this->deliveryPhone) || empty($this->deliveryEmail)) {
			return false;
		}

		return true;
	}
}
