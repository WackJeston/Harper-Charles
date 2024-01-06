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
			a.fileName,
			b.framing
			FROM banners AS b
			INNER JOIN banners AS b2 ON b2.id = b.parentId
			INNER JOIN asset AS a ON a.id = b.assetId
			WHERE b2.page = "home"
			AND b2.position = "top"
			AND b2.active = 1
			AND b.active = 1
			ORDER BY b.sequence ASC
		');

		$landingZoneBanners = cacheImages($landingZoneBanners, 2400, 2400);
		preloadImage($landingZoneBanners[0]->fileName, true);

    $categories = DB::select('SELECT
      c.id,
      c.title,
      c.subtitle,
      a.fileName
      FROM product_categories AS c
      LEFT JOIN product_category_images AS pci ON pci.categoryId=c.id AND pci.primary=1
			INNER JOIN asset AS a ON a.id = pci.assetId
      WHERE c.active=1
    ');

		$categories = cacheImages($categories, 1000, 1000);

		return view('public/home', compact(
			'landingZoneBanners', 
			'categories'
		));
	}
}
		