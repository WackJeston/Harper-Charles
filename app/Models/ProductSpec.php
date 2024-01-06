<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    use HasFactory;

    protected $table = 'product_spec';

    protected $fillable = [
      'productId',
      'label',
      'value',
			'description',
			'active',
			'sequence'
    ];

		protected static function booted() {
			static::created(function ($self) {
				$self->sequence = $self->id;
			});
		}
}
