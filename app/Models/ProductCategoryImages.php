<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryImages extends Model
{
  use HasFactory;

  protected $fillable = [
    'categoryId',
    'name',
    'filename',
    'primary',
  ];

  protected $guarded = [

  ];
}
