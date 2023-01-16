<?php

namespace App\Http\Controllers;

use DB;
use File;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use App\Models\ProductCategoryJoins;
use App\Models\ProductCategoryImages;
use App\Models\Products;

class AdminCategoryController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $categories = DB::table('product_categories')
      ->select('id', 'title', 'subtitle', 'description', 'show', DB::raw('COUNT(Join1.productId) as productCount'))

      ->leftJoin(DB::raw("(SELECT
        productId,
        categoryId
        FROM product_category_joins)
        Join1"),
        function($join)
        {
          $join->on('product_categories.id', '=', 'Join1.categoryId');
        })

        ->groupBy('id')
    ->get();

    // dd($categories);

    // $categories = ProductCategories::all();
    $joins = ProductCategoryJoins::all();
    $products = Products::all();

    return view('admin/categories', compact(
      'sessionUser',
      'categories',
      'joins',
      'products',
    ));
  }


  public function showCategory($category, $toggle)
  {
    ProductCategories::find($category)->update([
      'show' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/categories")->with('message', "Category is now on.");
    } else {
      return redirect("/admin/categories")->with('message', "Category is now off.");
    }
  }


  public function create(Request $request)
  {
    $this->validate($request, [
      'title' => 'required|max:100',
      'subtitle' => 'max:100',
      'description' => 'max:1000',
    ]);

    ProductCategories::create([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'show' => 0,
    ]);

    return redirect('/admin/categories')->with('message', 'Category created successfully.');
  }

  public function delete($id)
  {
    ProductCategories::find($id)->delete();

    return redirect('/admin/categories')->with('message', 'Category deleted successfully.');
  }
}
