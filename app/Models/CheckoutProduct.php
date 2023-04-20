<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutProduct extends Model
{
	use HasFactory;

	protected $fillable = [
		'checkoutId',
		'productId',
		'quantity',
	];
}
