<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
  use HasFactory;

  protected $fillable = [
		'parentId',
    'page',
    'position',
    'framing',
    'title',
    'description',
    'active',
    'name',
    'fileName',
  ];

}
