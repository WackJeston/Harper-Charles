<?php
namespace App\Http\Controllers;

use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\DataTable;
use App\DataForm;
use App\Models\Products;
use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductCategoryJoins;

class AdminCategoryProfileController extends Controller
{
  public function show($id)
  {
    if (ProductCategories::find($id) == null) {
      return redirect('/admin/categories')->withErrors('1' => ['Product not found']);
    }

		// Category
    $category = DB::SELECT('SELECT
			pc.*,
			COUNT(pcj.id) AS productCount,
			COUNT(pci.id) AS imageCount
			FROM product_categories AS pc
			LEFT JOIN product_category_joins AS pcj ON pcj.categoryId = pc.id
			LEFT JOIN product_category_images AS pci ON pci.categoryId = pc.id
			WHERE pc.id = ?
			GROUP BY pc.id
			LIMIT 1',
			[$id]
		);

    $category = $category[0];

		$editForm = new DataForm(request(), sprintf('/category-profileUpdate/%d', $id), 'Update');
		$editForm->addInput('text', 'title', 'Title', $category->title, 100, 1, true);
		$editForm->addInput('text', 'subtitle', 'Subtitle', $category->subtitle, 255, 1);
		$editForm->addInput('textarea', 'description', 'Description', $category->description, 5000, 1);
		$editForm = $editForm->render();

		// Images
		$primaryImage = DB::select('SELECT
			a.fileName
			FROM product_category_images AS pci
			INNER JOIN asset AS a ON a.id = pci.assetId
			WHERE pci.categoryId = ?
			AND pci.primary = 1
			LIMIT 1',
			[$id]
		);

		if (!empty($primaryImage)) {
			$primaryImage = cacheImage($primaryImage[0]->fileName, 1200, 1200);
		}

		$imagesForm = new DataForm(request(), sprintf('/category-profileAddImage/%d', $id), 'Add Image');
		$imagesForm->addInput('file', 'image', 'Image', null, null, null, true, null, ['multiple']);
		// $imagesForm->addInput('text', 'name', 'Rename', null, 100, 1);
		$imagesForm = $imagesForm->render();

    $imagesTable = new DataTable('product_category_images');
		$imagesTable->setQuery('SELECT 
			pci.id,
			pci.primary,
			pci.active,
			a.name,
			a.fileName
			FROM product_category_images AS pci
			INNER JOIN asset AS a ON a.id = pci.assetId
			WHERE pci.categoryId = ?', 
			[$id]
		);
		$imagesTable->addColumn('id', '#');
		$imagesTable->addColumn('name', 'Name', 2);
		$imagesTable->addColumn('primary', 'Primary', 1, false, 'setPrimary:categoryId:' . $id);
		$imagesTable->addColumn('active', 'Active', 1, false, 'toggle');
		$imagesTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$imagesTable->addJsButton('showDeleteWarning', ['string:Category', 'record:id', 'url:/category-profileDeleteImage/?'], 'fa-solid fa-trash-can', 'Delete Image');
		$imagesTable = $imagesTable->render();

		// Products
		$allProducts = DB::select(sprintf('SELECT 
			p.id AS value,
			p.title AS label,
			IF(pcj.id IS NOT NULL, true, false) AS `active`
			FROM products AS p
			LEFT JOIN product_category_joins AS pcj ON pcj.productId=p.id AND pcj.categoryId=%d
			GROUP BY p.id
			ORDER BY p.title', $id
		));

		$addProductForm = new DataForm(request(), sprintf('/category-profileAddProduct/%d', $id), 'Add Product');
		$addProductForm->setTitle('Add Existing Product');
		$addProductForm->addInput('select', 'product', 'Product', null, null, null, true);
		$addProductForm->populateOptions('product', $allProducts);
		$addProductForm = $addProductForm->render();

		$createProductForm = new DataForm(request(), sprintf('/category-profileCreateProduct/%d', $id), 'Create Product');
		$createProductForm->setTitle('Create New Product');
		$createProductForm->addInput('text', 'title', 'Title', null, 100, 1, true);
		$createProductForm->addInput('text', 'subtitle', 'Subtitle', null, 255, 1);
		$createProductForm->addInput('textarea', 'description', 'Description', null, 5000, 1);
		$createProductForm->addInput('text', 'productnumber', 'Product Number', null, 100, 1);
		$createProductForm->addInput('text', 'price', 'Price', null, 100, 1);
		$createProductForm = $createProductForm->render();

    $productsTable = new DataTable('products');
		$productsTable->setQuery('SELECT
			p.*
			FROM products AS p
			LEFT JOIN product_category_joins AS pcj ON pcj.productId = p.id
			WHERE pcj.categoryId = ?',
			[$id]
		);
		$productsTable->addColumn('id', '#');
		$productsTable->addColumn('title', 'Title', 2);
		$productsTable->addColumn('productNumber', 'Product Number', 2, true);
		$productsTable->addColumn('price', 'Price', 1, false, 'currency');
		$productsTable->addLinkButton('/product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$productsTable->addLinkButton('/category-profileRemoveProduct/' . $id . '/?', 'fa-solid fa-ban', 'Remove Product');
		$productsTable = $productsTable->render();

    return view('admin/category-profile', compact(
      'category',
			'editForm',
      'primaryImage',
			'imagesForm',
			'imagesTable',
			'addProductForm',
			'createProductForm',
			'productsTable',
    ));
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => ['required', 'max:100', Rule::unique('product_categories')->ignore($id)],
      'subtitle' => 'max:255',
      'description' => 'max:1000',
    ]);

    ProductCategories::where('id', $id)->update([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', 'Product updated.');
  }


  public function delete($id)
  {
    ProductCategories::find($id)->delete();

    return redirect("/admin/categories")->with('message', 'Category deleted.');
  }


  public function showCategory($category, $toggle)
  {
    ProductCategories::find($category)->update([
      'active' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/category-profile/" . $category)->with('message', "Category is now on.");
    } else {
      return redirect("/admin/category-profile/" . $category)->with('message', "Category is now off.");
    }
  }


  public function addImage(Request $request, $id)
  {
    $fileNames = storeImages($request, $id, 'category');

		foreach ($fileNames as $fileName) {
			ProductCategoryImages::create([
				'categoryId' => $id,
				'name' => !empty($request->name) ? $request->name : $fileName['old'],
				'assetId' => $fileName['id'],
				'primary' => 0,
			]);
		}

    return redirect("/admin/category-profile/$id")->with('message', 'Image uploaded.');
  }


  public function deleteImage($imageId)
  {
    $id = ProductCategoryImages::where('id', $imageId)->pluck('categoryId')->first();

		$fileName = DB::select('SELECT
			a.fileName
			FROM product_category_images AS pci
			INNER JOIN asset AS a ON a.id = pci.assetId
			WHERE pci.id = ?
			LIMIT 1',
			[$imageId]
		);

    ProductCategoryImages::find($imageId)->delete();

    return redirect("/admin/category-profile/$id")->with('message', "Image #$imageId has been deleted.");
  }


  public function primaryImage($imageId)
  {
    $id = ProductCategoryImages::where('id', $imageId)->pluck('categoryId')->first();
    $name = ProductCategoryImages::where('id', $imageId)->pluck('name')->first();

    ProductCategoryImages::where('categoryId', $id)->update([
      'primary' => 0,
    ]);

    ProductCategoryImages::find($imageId)->update([
      'primary' => 1,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', "$name is now the primary image.");
  }


  public function addProduct(Request $request, $id)
  {
    $request->validate([
      'product' => 'required',
    ]);

    ProductCategoryJoins::create([
      'productId' => $request->product,
      'categoryId' => $id,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', 'Product added.');
  }

  public function createProduct(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|max:100',
      'subtitle' => 'max:255',
      'description' => 'max:5000',
      'productnumber' => 'required|unique:products|max:100',
      'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
    ]);

    $product = Products::create([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'productnumber' => $request->productnumber,
      'price' => $request->price,
    ]);

    ProductCategoryJoins::create([
      'productId' => $product->id,
      'categoryId' => $id,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', 'Product created.');
  }

  public function removeProduct($id, $productId)
  {
    ProductCategoryJoins::where('productId', $productId)->where('categoryId', $id)->delete();

    return redirect("/admin/category-profile/$id")->with('message', "Product #$productId has been removed.");
  }
}
