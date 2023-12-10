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
  ];

  protected $guarded = [

  ];

  protected static function booted() {
    static::updated(function ($self) {
			if ($self->isDirty('primary') && $self->primary == 1) {
				$oldPrimary = ProductCategoryImages::where('primary', 1)->first();
				$oldPrimary->primary = 0;
				$oldPrimary->update();
			}
    });

		static::created(function ($self) {
			if ($self->isDirty('primary') && $self->primary == 1) {
				$oldPrimary = ProductCategoryImages::where('primary', 1)->first();
				$oldPrimary->primary = 0;
				$oldPrimary->update();
			}
    });
  }
}
