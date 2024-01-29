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
    'orbitalVisionId',
    'price',
    'maxQuantity',
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
}
