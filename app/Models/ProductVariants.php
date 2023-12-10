<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
  use HasFactory;

  protected $fillable = [
    'parentVariantId',
    'title',
		'type',
    'assetId',
    'colour',
    'active',
  ];

  protected $guarded = [

  ];
}
