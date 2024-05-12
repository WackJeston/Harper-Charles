<?php

namespace App\Models;

use DB;
use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
  use HasFactory;

	protected $errors = [];
  protected $fillable = [
    'title',
    'subtitle',
    'description',
    'productnumber',
    'orbitalVisionId',
    'price',
    'maxQuantity',
    'stock',
    'startDate',
    'endDate',
		'active',
  ];

  protected static function booted() {
    static::deleting(function ($self) {
      $filesNames = ProductImages::where('productId', $self->id)->pluck('fileName');

      foreach ($filesNames as $fileName) {
        Storage::delete($fileName);
      }
    });
  }

	public function errors():array {
		return $this->errors;
	}

	public function available(int $quantity = null):bool {
		if ($this->active == 0) {
			$this->errors[] = 'Product not currently available.';
			return false;
		}

		if ((!is_null($this->startDate) && strtotime($this->startDate) > time()) || (!is_null($this->endDate) && strtotime($this->endDate) < time())) {
			$this->errors[] = 'Product not currently available.';
			return false;
		}

		$pastPurchaseQuantity = DB::select('SELECT 
			SUM(ol.quantity) as quantity 
			FROM order_lines AS ol
			INNER JOIN orders AS o ON o.id = ol.orderId
			WHERE ol.productId = ?
			AND o.userId = ?
			AND o.type != "basket"', 
			[
				$this->id, 
				auth()->id()
			]
		)[0]->quantity;

		$remainingQuantity = $this->maxQuantity - $pastPurchaseQuantity;

		if (!is_null($quantity) && $remainingQuantity < $quantity) {
			if ($remainingQuantity > 0) {
				$this->errors[] = sprintf('Max purchase quantity of %d.', $remainingQuantity);
			} else {
				$this->errors[] = 'You have already purchased the maximum amount of this product.';
			}
			
			return false;
		}

		if (!is_null($quantity) && !is_null($this->stock) && $this->stock < $quantity) {
			$this->errors[] = sprintf('Not enough stock, only %d available.', $this->stock);
			return false;
		}

		return true;
	}
}
