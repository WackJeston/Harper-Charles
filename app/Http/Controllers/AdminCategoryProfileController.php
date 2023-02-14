<?php
namespace App\Http\Controllers;

use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    $category = DB::table('product_categories')
      ->select('id', 'title', 'subtitle', 'description', 'show', 'created_at', DB::raw('COUNT(Join1.productId) as productCount'))

      ->leftJoin(DB::raw("(SELECT
        productId,
        categoryId
        FROM product_category_joins)
        Join1"),
        function($join)
        {
          $join->on('product_categories.id', '=', 'Join1.categoryId');
        })

        ->where('id', $id)
        ->groupBy('id')
        ->limit(1)
    ->get();

    $category = $category[0];

    $images = ProductCategoryImages::all()->where('categoryId', $id);
    $imageCount = count($images);

    if ($imageCount == 1) {
      ProductCategoryImages::where('categoryId', $id)->update([
        'primary' => 1,
      ]);
    }

    $primaryImage = ProductCategoryImages::where([['categoryId', $id], ['primary', 1]])->pluck('fileName')->first();

    $products = DB::table('products')
      ->select('id', 'title', 'subtitle', 'description', 'productNumber')

      ->leftJoin(DB::raw("(SELECT
        productId,
        categoryId
        FROM product_category_joins)
        Join1"),
        function($join)
        {
          $join->on('products.id', '=', 'Join1.productId');
        })

      ->where('Join1.categoryId', $id)
      ->groupBy('id')
    ->get();

    $productIds = [];

    foreach ($products as $i => $product) {
      array_push($productIds, $product->id);
    }

    $allProducts = Products::select('id', 'title')->whereNotIn('id', $productIds)->get();


    return view('admin/category-profile', compact(
      'sessionUser',
      'category',
      'images',
      'imageCount',
      'primaryImage',
      'products',
      'allProducts',
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
