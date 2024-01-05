<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class ProductImages extends Model
{
  use HasFactory;

  protected $fillable = [
    'productId',
    'assetId',
    'primary',
		'active',
		'sequence'
  ];

	protected static function booted() {
		static::created(function ($self) {
			$self->sequence = $self->id;
    });
	}
}
