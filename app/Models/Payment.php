<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  use HasFactory;

	protected $fillable = [
		'orderId',
		'stripeReference',
		'type',
		'status',
		'detail',
		'method',
		'amount',
		'captured',
	];
}
