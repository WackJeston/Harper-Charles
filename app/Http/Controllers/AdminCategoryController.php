<?php

namespace App\Http\Controllers;

use DB;
use File;
use Illuminate\Http\Request;
use App\dataTable;
use App\Models\ProductCategories;
use App\Models\ProductCategoryJoins;
use App\Models\ProductCategoryImages;
use App\Models\Products;

class AdminCategoryController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $categoriesTable = new DataTable();
		$categoriesTable->setQuery('SELECT
		 pc.*,
		 COUNT(p.id) AS products
		 FROM product_categories AS pc
		 LEFT JOIN product_category_joins AS p ON pc.id = p.categoryId
		 GROUP BY pc.id
		');

		$categoriesTable->addColumn('id', '#');
		$categoriesTable->addColumn('title', 'Title', 2);
		$categoriesTable->addColumn('subtitle', 'Subtitle', 2);
		$categoriesTable->addColumn('products', 'Products');

		$categoriesTable->addButton('category-profile/?', 'fa-solid fa-folder-open', 'Open Record');

    return view('admin/categories', compact(
      'sessionUser',
			'categoriesTable'
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
