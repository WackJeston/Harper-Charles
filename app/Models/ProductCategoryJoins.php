<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryJoins extends Model
{
  use HasFactory;

  protected $fillable = [
    'productId',
    'categoryId',
  ];

  protected $guarded = [

  ];
}
