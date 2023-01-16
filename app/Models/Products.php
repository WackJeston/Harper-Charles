<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'subtitle',
    'description',
    'productnumber',
    'price',
  ];

  protected static function booted() {
    static::deleting(function ($self) {
      $filesNames = ProductImages::where('productId', $self->id)->pluck('fileName');

      foreach ($filesNames as $fileName) {
        deleteS3($fileName);
      }

      ProductImages::where('productId', $self->id)->delete();
      ProductCategoryJoins::where('productId', $self->id)->delete();
      ProductVariantJoins::where('productId', $self->id)->delete();
      Cart::where('productId', $self->id)->delete();
    });
  }
}
