<?php
namespace App\Http\Controllers;

use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\dataTable;
use App\Models\Products;
use App\Models\ProductCategories;
use App\Models\ProductCategoryImages;
use App\Models\ProductCategoryJoins;

class AdminCategoryProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

    if (ProductCategories::find($id) == null) {
      return redirect('/admin/categories');
    }

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

    $imagesTable = new DataTable('product_category_images');
		$imagesTable->setQuery('SELECT * FROM product_category_images WHERE categoryId = ?', [$id]);

		$imagesTable->addColumn('id', '#');
		$imagesTable->addColumn('name', 'Name', 2);
		$imagesTable->addColumn('primary', 'Primary', 1, false, 'setPrimary');

		$imagesTable->addButton(null, 'fa-solid fa-eye', 'Delete Image', 'showImage(image.fileName)');
		$imagesTable->addButton('/category-profileDeleteImage/?', 'fa-solid fa-trash', 'Delete Image');

		$imagesTable = $imagesTable->display(true);

    if ($category->imageCount == 1) {
      ProductCategoryImages::where('categoryId', $id)->update([
        'primary' => 1,
      ]);
    }

    $primaryImage = ProductCategoryImages::where([['categoryId', $id], ['primary', 1]])->pluck('fileName')->first();

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
		$productsTable->addColumn('productNumber', 'Product Number', 2);
		$productsTable->addColumn('price', 'Price', 1, false, 'currency');

		$productsTable->addButton('/product-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$productsTable->addButton('/category-profileRemoveProduct/' . $id . '/?', 'fa-solid fa-ban', 'Remove Product');

		$productsTable = $productsTable->display(true);

		// dd($productsTable);

    return view('admin/category-profile', compact(
      'sessionUser',
      'category',
      'primaryImage',
			'imagesTable',
			'productsTable',
    ));
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => ['required', 'max:100', Rule::unique('product_categories')->ignore($id)],
      'subtitle' => 'max:100',
      'description' => 'max:1000',
    ]);

    ProductCategories::where('id', $id)->update([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', 'Product updated successfully.');
  }


  public function delete($id)
  {
    ProductCategories::find($id)->delete();

    return redirect("/admin/categories")->with('message', 'Category deleted successfully.');
  }


  public function showCategory($category, $toggle)
  {
    ProductCategories::find($category)->update([
      'show' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/category-profile/" . $category)->with('message', "Category is now on.");
    } else {
      return redirect("/admin/category-profile/" . $category)->with('message', "Category is now off.");
    }
  }


  public function storeImage(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required|max:100|unique_custom:product_category_images,name,categoryId,' . $id,
      'image' => 'required|mimes:jpg,jpeg,png,svg',
    ],
    [
      'name.unique_custom' => 'Image name must be unique.',
    ]);

    $mimeType = str_replace('image/', '', $request->file('image')->getClientMimeType());
    if ($mimeType == 'svg+xml') { $mimeType = 'svg'; }
    else if ($mimeType == 'jpeg') { $mimeType = 'jpg'; }
    $fileName = 'category-image-' . $id . '-' . $_SERVER['REQUEST_TIME'] . '.' . $mimeType;

    if ($request->hasFile('image')) {
      $request->file('image')->move('assets', $fileName);

      uploadS3($fileName);
    }

    ProductCategoryImages::create([
      'categoryId' => $id,
      'name' => $request->name,
      'filename' => $fileName,
      'primary' => 0,
    ]);

    return redirect("/admin/category-profile/$id")->with('message', 'Image uploaded successfully.');
  }


  public function deleteImage($imageId)
  {
    $id = ProductCategoryImages::where('id', $imageId)->pluck('categoryId')->first();
    $name = ProductCategoryImages::where('id', $imageId)->pluck('name')->first();
    $fileName = ProductCategoryImages::where('id', $imageId)->pluck('fileName')->first();
    
    deleteS3($fileName);

    ProductCategoryImages::find($imageId)->delete();

    return redirect("/admin/category-profile/$id")->with('message', "$name has been deleted.");
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

    return redirect("/admin/category-profile/$id")->with('message', 'Product added successfully.');
  }

  public function createProduct(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|max:100',
      'subtitle' => 'max:100',
      'description' => 'max:1000',
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

    return redirect("/admin/category-profile/$id")->with('message', 'Product created successfully.');
  }

  public function removeProduct($id, $productId)
  {
    ProductCategoryJoins::where('productId', $productId)->where('categoryId', $id)->delete();

    return redirect("/admin/category-profile/$id")->with('message', "Product #$productId has been removed.");
  }
}
