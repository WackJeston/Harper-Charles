<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryImages extends Model
{
  use HasFactory;

  protected $fillable = [
    'categoryId',
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
