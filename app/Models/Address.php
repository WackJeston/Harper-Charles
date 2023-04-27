<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;

  protected $fillable = [
    'userId',
		'type',
    'defaultBilling',
    'defaultShipping',
    'firstName',
    'lastName',
    'company',
    'line1',
    'line2',
    'line3',
    'city',
    'region',
    'country',
    'postCode',
    'phone',
    'email',
    'deliveryNote',
  ];
}
