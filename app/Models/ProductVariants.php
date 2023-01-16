<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
  use HasFactory;

  protected $fillable = [
    'parentvariantid',
    'title',
    'show',
  ];

  protected $guarded = [

  ];

  protected static function booted() {
    static::deleting(function ($self) {
      ProductVariants::where('parentVariantId', $self->id)->delete();

      $cartIds = CartVariants::select('cartId')->where('variantId', $self->id)->get();

      foreach ($cartIds as $i => $item) {
        $variantIds = Cart::select('variantId')->where('cartId', $item)->get();
        foreach ($variantIds as $i2 => $variant) {
          CartVariants::where('variantId', $variant)->delete();
        }
        Cart::where('cartId', $item)->delete();
      }

      CartVariants::where('variantId', $self->id)->delete();
    });
  }
}
