<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  protected $table = 'cart';

  protected $fillable = [
    'userId',
    'productId',
    'variantIds',
    'quantity',
  ];

  protected static function booted() {
    static::deleting(function ($self) {
      CartVariants::where('cartId', $self->id)->delete();
    });
  }
}
