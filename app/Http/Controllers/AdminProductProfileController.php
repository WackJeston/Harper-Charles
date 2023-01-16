<?php
namespace App\Http\Controllers;

use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\ProductCategories;
use App\Models\ProductCategoryJoins;
use App\Models\ProductVariants;
use App\Models\ProductVariantJoins;

class AdminProductProfileController extends Controller
{
  public function show($id)
  {
    $sessionUser = auth()->user();

    if (Products::find($id) == null) {
      return redirect('/admin/products');
    }

    $product = DB::table('products')
      ->select('id', 'title', 'subtitle', 'description', 'productNumber', 'price', 'created_at', DB::raw('COUNT(Join1.categoryId) as categoryCount'))

      ->leftJoin(DB::raw("(SELECT
        productId,
        categoryId
        FROM product_category_joins)
        Join1"),
        function($join)
        {
          $join->on('products.id', '=', 'Join1.productId');
        })

        ->where('id', $id)
        ->groupBy('id')
        ->limit(1)
    ->get();

    $product = $product[0];

    $images = ProductImages::all()->where('productId', $id);
    $imageCount = count($images);

    if ($imageCount == 1) {
      ProductImages::where('productId', $id)->update([
        'primary' => 1,
      ]);
    }

    $primaryImage = ProductImages::where([['productId', $id], ['primary', 1]])->pluck('fileName')->first();

    $categories = DB::table('product_categories')
      ->select('id', 'title', 'subtitle')

      ->leftJoin(DB::raw("(SELECT
        productId,
        categoryId
        FROM product_category_joins)
        Join1"),
        function($join)
        {
          $join->on('product_categories.id', '=', 'Join1.categoryId');
        })

      ->where('Join1.productId', $id)
      ->groupBy('id')
    ->get();

    $categoryIds = [];

    foreach ($categories as $i => $category) {
      array_push($categoryIds, $category->id);
    }

    $allCategories = ProductCategories::select('id', 'title')->whereNotIn('id', $categoryIds)->get();

    $variants = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
      pv2.title AS parent
      FROM product_variant_joins AS pvj
      INNER JOIN product_variants AS pv ON pv.id=pvj.variantId
      INNER JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId
      WHERE pvj.productId = "%d"
      ORDER BY pv2.title, pv.title
    ', $id));

    $allVariantRecords = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
      GROUP_CONCAT(pv2.id, "|", pv2.title ORDER BY pv2.title) AS options
      FROM product_variants AS pv
      INNER JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
      WHERE pv.parentVariantId = 0
      AND pv2.id NOT IN (SELECT variantId FROM product_variant_joins WHERE productId = "%d")
      GROUP BY pv.id
      ORDER BY pv.title
    ', $id));

    $allVariants = [];

    foreach ($allVariantRecords as $i => $variant) {
      $optionsPre = explode(',', $variant->options);

      $allVariants[$i] = [
        $id = $variant->id,
        $title = $variant->title,
      ];

      foreach ($optionsPre as $i2 => $option) {
        $allVariants[$i][2][$i2] = explode('|', $option);
      }
    }

    return view('/admin/product-profile', compact(
      'sessionUser',
      'product',
      'images',
      'imageCount',
      'primaryImage',
      'categories',
      'allCategories',
      'variants',
      'allVariants',
    ));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => ['required', 'max:100', Rule::unique('products')->ignore($id)],
      'subtitle' => 'max:100',
      'description' => 'max:1000',
      'productnumber' => ['required', 'max:100', Rule::unique('products')->ignore($id)],
      'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
    ]);

    Products::where('id', $id)->update([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'productnumber' => $request->productnumber,
      'price' => $request->price,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Product updated successfully.');
  }


  public function delete($id)
  {
    Products::find($id)->delete();

    return redirect("/admin/products")->with('message', 'Product deleted successfully.');
  }


  public function storeImage(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required|max:100|unique_custom:product_images,name,productId,' . $id,
      'image' => 'required|mimes:jpg,jpeg,png,svg',
    ],
    [
      'name.unique_custom' => 'Image name must be unique.',
    ]);

    $mimeType = str_replace('image/', '', $request->file('image')->getClientMimeType());
    if ($mimeType == 'svg+xml') { $mimeType = 'svg'; }
    else if ($mimeType == 'jpeg') { $mimeType = 'jpg'; }
    $fileName = $id . '-' . str_replace(' ', '-', strtolower($request->name)) . '.' . $mimeType;

    if ($request->hasFile('image')) {
      $request->file('image')->move('assets', $fileName);

      uploadS3($fileName);
    }

    ProductImages::create([
      'productId' => $id,
      'name' => $request->name,
      'filename' => $fileName,
      'primary' => 0,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Image uploaded successfully.');
  }

  public function deleteImage($imageId)
  {
    $id = ProductImages::where('id', $imageId)->pluck('productId')->first();
    $fileName = ProductImages::where('id', $imageId)->pluck('fileName')->first();
    $name = ProductImages::where('id', $imageId)->pluck('name')->first();

    ProductImages::where('id', $imageId)->delete();
    deleteS3($fileName);

    return redirect("/admin/product-profile/$id")->with('message', "$name has been deleted.");
  }

  public function primaryImage($imageId)
  {
    $id = ProductImages::where('id', $imageId)->pluck('productId')->first();
    $name = ProductImages::where('id', $imageId)->pluck('name')->first();

    ProductImages::where('productId', $id)->update([
      'primary' => 0,
    ]);

    ProductImages::where('id', $imageId)->update([
      'primary' => 1,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', "$name is now the primary image.");
  }


  public function addCategory(Request $request, $id)
  {
    $request->validate([
      'category' => 'required',
    ]);

    ProductCategoryJoins::create([
      'productId' => $id,
      'categoryId' => $request->category,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Category added successfully.');
  }

  public function removeCategory($id, $categoryId)
  {
    ProductCategoryJoins::where('productId', $id)->where('categoryId', $categoryId)->delete();

    return redirect("/admin/product-profile/$id")->with('message', "Category #$categoryId has been removed.");
  }

  public function addVariant(Request $request, $id)
  {
    $request->validate([
      'variant' => 'required',
    ]);

    ProductVariantJoins::create([
      'productId' => $id,
      'variantId' => $request->variant,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Variant added successfully.');
  }

  public function removeVariant($id, $variantId)
  {
    ProductVariantJoins::where('productId', $id)->where('variantId', $variantId)->delete();

    return redirect("/admin/product-profile/$id")->with('message', "Variant #$variantId has been removed.");
  }
}
