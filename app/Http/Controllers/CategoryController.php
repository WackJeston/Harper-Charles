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
				b.fileName,
				b.framing
				FROM banners AS b
				INNER JOIN banners AS b2 ON b2.id = b.parentId
				WHERE b2.page = "shop"
				AND b2.position = "top"
				AND b2.active = 1
				AND b.active = 1'
			);
			preloadImage($banners[0]->fileName);

			$items = DB::select('SELECT
				pc.id,
				pc.title,
				pci.fileName
				FROM product_categories AS pc
				LEFT JOIN product_category_images AS pci ON pci.categoryId = pc.id AND pci.primary = 1
				WHERE pc.show = 1'
			);

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

			$banners = DB::select('SELECT `fileName` FROM product_category_images WHERE categoryId = ?', [$id]);
			preloadImage($banners[0]->fileName);

			$items = DB::select('SELECT
				p.*,
				pi.fileName
				FROM products AS p
				INNER JOIN product_category_joins AS pcj ON pcj.productId = p.id
				LEFT JOIN product_images AS pi ON pi.productId = p.id AND pi.primary = 1
				WHERE pcj.categoryId = ?', 
				[$id]
			);

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
