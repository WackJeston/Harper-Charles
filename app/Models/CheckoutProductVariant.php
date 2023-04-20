<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutProductVariant extends Model
{
	use HasFactory;

	protected $fillable = [
		'checkoutProductId',
		'variantId',
	];
}
