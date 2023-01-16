<?php

namespace App\Http\Controllers;

use DB;
use App\Models\LandingZoneCarousels;
use App\Models\LandingZones;
use App\Models\productCategories;
use App\Models\productCategoryImages;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $landingZoneCarouselPre = LandingZoneCarousels::where('landingZoneId', 1)
      ->orderBy('primary', 'desc')
    ->get();

    $landingZoneCarousel = $landingZoneCarouselPre->toJson();
    $landingZoneCarouselShow = LandingZones::where('id', 1)->pluck('show')->first();

    $categories = DB::select('SELECT
      c.id,
      c.title,
      c.subtitle,
      i.fileName
      FROM product_categories AS c
      LEFT JOIN product_category_images AS i ON i.categoryId=c.id AND i.primary=1
      WHERE c.show=1
    ');

    return view('home', compact(
      'sessionUser',
      'landingZoneCarousel',
      'landingZoneCarouselShow',
      'categories',
    ));
  }
}
