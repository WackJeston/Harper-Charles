<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingZoneCarousels extends Model
{
  use HasFactory;

  protected $fillable = [
    'landingZoneId',
    'name',
    'title',
    'subtitle',
    'fileName',
    'primary',
  ];

  protected $guarded = [

  ];
}
