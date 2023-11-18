<?php

namespace App\Http\Controllers;

use DB;
use File;
use Illuminate\Http\Request;
use App\DataTable;
use App\DataForm;
use App\Models\ProductCategories;
use App\Models\ProductCategoryJoins;
use App\Models\ProductCategoryImages;
use App\Models\Products;

class AdminCategoryController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

		$createForm = new DataForm(request(), '/categoryCreate', 'Add');
		$createForm->addInput('text', 'title', 'Title', null, 100, 1, true);
		$createForm->addInput('text', 'subtitle', 'Subtitle', null, 255, 1);
		$createForm->addInput('textarea', 'description', 'Description', null, 5000, 1);
		$createForm = $createForm->render();

    $categoriesTable = new DataTable('product_categories');
		$categoriesTable->setQuery('SELECT
		 pc.*,
		 COUNT(p.id) AS products
		 FROM product_categories AS pc
		 LEFT JOIN product_category_joins AS p ON pc.id = p.categoryId
		 GROUP BY pc.id
		');
		$categoriesTable->addColumn('id', '#');
		$categoriesTable->addColumn('title', 'Title', 2);
		// $categoriesTable->addColumn('subtitle', 'Subtitle', 2, true);
		$categoriesTable->addColumn('show', 'Active', 1, false, 'toggle');
		$categoriesTable->addColumn('products', 'Products');
		$categoriesTable->addLinkButton('category-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$categoriesTable = $categoriesTable->render();

    return view('admin/categories', compact(
      'sessionUser',
			'createForm',
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

    return redirect('/admin/categories')->with('message', 'Category created.');
  }

  public function delete($id)
  {
    ProductCategories::find($id)->delete();

    return redirect('/admin/categories')->with('message', 'Category deleted.');
  }
}
