<?php

namespace App\Http\Controllers;

use DB;
use App\Models\LandingZoneCarousels;
use App\Models\LandingZones;
use App\Models\productCategories;
use App\Models\productCategoryImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
  public function show()
  {
		$landingZoneBanners = DB::select('SELECT
			b.id,
			b.title,
			b.description,
			b.fileName,
			b.framing
			FROM banners AS b
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			WHERE b2.page = "home"
			AND b2.position = "top"
			AND b2.active = 1
			AND b.active = 1
		');

		$landingZoneBanners = cacheImages($landingZoneBanners, true, 1000, 1000);
		preloadImage($landingZoneBanners[0]->fileName);

    $categories = DB::select('SELECT
      c.id,
      c.title,
      c.subtitle,
      i.fileName
      FROM product_categories AS c
      LEFT JOIN product_category_images AS i ON i.categoryId=c.id AND i.primary=1
      WHERE c.show=1
    ');

		$categories = cacheImages($categories, true, 600, 600);

    return view('home', compact(
      'landingZoneBanners',
      'categories',
    ));
  }
}
