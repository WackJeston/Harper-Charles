<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductImages;


class CategoryController extends Controller
{
  public function show($id)
  {
		if ($id == 0) {
			$categories = DB::select('SELECT
				pc.id,
				pc.title,
				pci.fileName
				FROM product_categories AS pc
				LEFT JOIN product_category_images AS pci ON pci.categoryId = pc.id AND pci.primary = 1
				WHERE pc.show = 1'
			);

			return view('public/category', compact(
				'categories'
			));
		
		} else {
			$category = ProductCategories::find($id);
			$banners = DB::select('SELECT `fileName` FROM product_category_images WHERE categoryId = ?', [$id]);
			$products = DB::select('SELECT
				p.*
				FROM products AS p
				INNER JOIN product_category_joins AS pcj ON pcj.productId = p.id
				WHERE pcj.categoryId = ?', 
				[$id]
			);

			return view('public/category', compact(
				'category',
				'banners',
				'products'
			));
		}
  }
}
