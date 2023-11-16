<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'subtitle',
    'description',
    'filename',
    'show',
  ];

  protected $guarded = [

  ];

  protected static function booted() {
    static::deleting(function ($self) {
      $filesNames = ProductCategoryImages::where('categoryId', $self->id)->pluck('fileName');

      foreach ($filesNames as $fileName) {
        Storage::delete($fileName);
      }
    });
  }
}
