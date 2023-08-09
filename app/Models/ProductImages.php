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
    'name',
    'filename',
    'primary',
  ];

  protected $guarded = [

  ];
}
