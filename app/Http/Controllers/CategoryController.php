<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductImages;


class CategoryController extends Controller
{
  public function show(int $id = 0)
  {
		if ($id == 0) {
			$categories = true;
			$bannerTitle = 'Shop';
			$bannerDescription = 'Browse our ranges of bespoke products.';
			$url = 'category';

			$banners = DB::select('SELECT
				b.id,
				b.title,
				b.description,
				a.fileName,
				b.framing
				FROM banners AS b
				INNER JOIN banners AS b2 ON b2.id = b.parentId
				INNER JOIN asset AS a ON a.id = b.assetId
				WHERE b2.page = "shop"
				AND b2.position = "top"
				AND b2.active = 1
				AND b.active = 1'
			);
			
			$banners = cacheImages($banners, 2400, 2400);
			if (!empty($banners)) {
				preloadImage($banners[0]->fileName, true);
			}

			$items = DB::select('SELECT
				pc.id,
				pc.title,
				a.fileName
				FROM product_categories AS pc
				LEFT JOIN product_category_images AS pci ON pci.categoryId = pc.id AND pci.primary = 1
				INNER JOIN asset AS a ON a.id = pci.assetId
				WHERE pc.active = 1'
			);

			$items = cacheImages($items, 1000, 1000);

			return view('public/category', compact(
				'categories',
				'bannerTitle',
				'bannerDescription',
				'url',
				'banners',
				'items'
			));
		
		} else {
			$categories = false;
			$bannerTitle = null;
			$bannerDescription = null;
			$url = 'product';

			$category = ProductCategories::find($id);

			$banners = DB::select('SELECT 
				a.fileName
				FROM product_category_images AS pci
				INNER JOIN asset AS a ON a.id = pci.assetId
				WHERE pci.categoryId = ?
				AND pci.active = 1', 
				[$id]
			);

			$banners = cacheImages($banners, 2400, 2400);
			if (!empty($banners)) {
				preloadImage($banners[0]->fileName, true);
			}

			$items = DB::select('SELECT
				p.*,
				a.fileName
				FROM products AS p
				INNER JOIN product_category_joins AS pcj ON pcj.productId = p.id
				LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
				LEFT JOIN asset AS a ON a.id = pi.assetId
				WHERE pcj.categoryId = ?
				AND p.active = 1', 
				[$id]
			);

			$items = cacheImages($items, 1000, 1000);

			return view('public/category', compact(
				'categories',
				'bannerTitle',
				'bannerDescription',
				'url',
				'category',
				'banners',
				'items'
			));
		}
  }
}
