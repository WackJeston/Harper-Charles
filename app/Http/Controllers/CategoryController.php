<?php

namespace App\Http\Controllers;

use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductImages;


class CategoryController extends PublicController
{
  public function show(int $id = 0)
  {
		if ($id == 0) {
			if (!$records = getCachedRecords('public-page-shop')) {
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
					AND b.active = 1
					ORDER BY b.sequence ASC'
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

				$records = cacheRecords('public-page-shop', [
					'categories' => $categories,
					'bannerTitle' => $bannerTitle,
					'bannerDescription' => $bannerDescription,
					'url' => $url,
					'banners' => $banners,
					'items' => $items
				]);
			}

			$categories = $records['categories'];
			$bannerTitle = $records['bannerTitle'];
			$bannerDescription = $records['bannerDescription'];
			$url = $records['url'];
			$banners = $records['banners'];
			$items = $records['items'];			

			return view('public/category', compact(
				'categories',
				'bannerTitle',
				'bannerDescription',
				'url',
				'banners',
				'items'
			));
		
		} else {
			if (!$category = ProductCategories::find($id)) {
				return redirect('/shop')->withErrors(['1' => 'Category not found']);
			}
	
			if ($category->active != 1) {
				return redirect('/shop')->withErrors(['1' => 'Category not currently available']);
			}

			if (!$records = getCachedRecords('public-page-category-' . $id)) {
				$categories = false;
				$bannerTitle = null;
				$bannerDescription = null;
				$url = 'product';

				$banners = DB::select('SELECT 
					a.fileName
					FROM product_category_images AS pci
					INNER JOIN asset AS a ON a.id = pci.assetId
					WHERE pci.categoryId = ?
					AND pci.active = 1
					ORDER BY pci.sequence ASC', 
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

				$records = cacheRecords('public-page-category-' . $id, [
					'categories' => $categories,
					'bannerTitle' => $bannerTitle,
					'bannerDescription' => $bannerDescription,
					'url' => $url,
					'category' => $category,
					'banners' => $banners,
					'items' => $items
				]);
			}

			$categories = $records['categories'];
			$bannerTitle = $records['bannerTitle'];
			$bannerDescription = $records['bannerDescription'];
			$url = $records['url'];
			$category = $records['category'];
			$banners = $records['banners'];
			$items = $records['items'];

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
