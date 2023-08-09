<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductImages;


class ProductsController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

    $imageCount = ProductImages::where('primary', 1)->count();

    $products = DB::select('SELECT
      p.id,
      p.title,
      p.subtitle,
      c.categoryId AS categoryId,
      i.fileName
      FROM products AS p
      INNER JOIN product_category_joins AS c ON c.productId=p.id
      LEFT JOIN product_images AS i ON i.productId=p.id AND i.primary=1
    ');

    $categories = ProductCategories::all()->where('show', 1);
    $categoryImages = ProductCategoryImages::all();

    $initialCategory = $id;

    return view('public/products', compact(
      'sessionUser',
      'products',
      'categories',
      'categoryImages',
      'initialCategory'
    ));
  }
}
