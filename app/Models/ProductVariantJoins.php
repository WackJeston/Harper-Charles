<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantJoins extends Model
{
  use HasFactory;

  protected $fillable = [
    'productId',
    'variantId',
  ];

  protected $guarded = [

  ];
}
