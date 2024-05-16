<?php
namespace App\Http\Controllers;

use DB;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\DataTable;
use App\DataForm;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\ProductSpec;
use App\Models\ProductStock;
use App\Models\ProductCategories;
use App\Models\ProductCategoryJoins;
use App\Models\ProductVariants;
use App\Models\ProductVariantJoins;

class AdminProductProfileController extends AdminController
{
  public function show($id)
  {
		if (Products::find($id) == null) {
      return redirect('/admin/products')->withErrors(['1' => 'Product not found']);
    }

		// Product
    $product = DB::select('SELECT 
			p.*,
			DATE_FORMAT(p.updated_at, "%%d/%%m/%%Y %%H:%%i:%%s") AS updatedAt,
			COUNT(pcj.id) AS categoryCount
			FROM products AS p
			LEFT JOIN product_category_joins AS pcj ON pcj.productId=p.id
			WHERE p.id = ?
			GROUP BY p.id', [$id]
		);

    $product = $product[0];

		$editForm = new DataForm(request(), sprintf('/product-profileUpdate/%d', $id), 'Update');
		$editForm->addInput('text', 'title', 'Title', $product->title, 100, 1, true);
		$editForm->addInput('text', 'subtitle', 'Subtitle', $product->subtitle, 255, 1);
		$editForm->addInput('textarea', 'description', 'Description', $product->description, 5000, 1);
		$editForm->addInput('text', 'productnumber', 'Product Number', $product->productNumber, 100, 1);
		$editForm->addInput('text', 'orbitalVisionId', 'Orbital Vision', $product->orbitalVisionId, 100, 1);
		$editForm->addInput('text', 'price', 'Price', $product->price, 100, 1, true);
		$editForm->addInput('num', 'maxQuantity', 'Max Purchase Quantity', $product->maxQuantity, 999, 1);
		$editForm->addInput('datetime', 'startDate', 'Start Date', !is_null($product->startDate) ? $product->startDate : '0000-00-00 00:00:00', null, null);
		$editForm->addInput('datetime', 'endDate', 'End Date', !is_null($product->endDate) ? $product->endDate : '0000-00-00 00:00:00', null, null);
		$editForm = $editForm->render();

		// Categories
		$allCategories = DB::select('SELECT 
			pc.id AS value,
			pc.title AS label,
			IF(pcj.id IS NOT NULL, true, false) AS `active`
			FROM product_categories AS pc
			LEFT JOIN product_category_joins AS pcj ON pcj.categoryId = pc.id AND pcj.productId = ?
			GROUP BY pc.id
			ORDER BY pc.title', 
			[$id]
		);

		$categoryForm = new DataForm(request(), sprintf('/product-profileAddCategory/%d', $id), 'Add Category');
		$categoryForm->addInput('select', 'category', 'Category', null, null, null, true);
		$categoryForm->populateOptions('category', $allCategories);
		$categoryForm = $categoryForm->render();

		$categoriesTable = new DataTable('product_categories');
		$categoriesTable->setQuery('SELECT 
			pc.*
			FROM product_categories AS pc
			LEFT JOIN product_category_joins AS pcj ON pcj.categoryId=pc.id
			WHERE pcj.productId = ?
			GROUP BY pc.id', 
			[$id]
		);
		$categoriesTable->addColumn('id', '#');
		$categoriesTable->addColumn('title', 'Title');
		$categoriesTable->addColumn('subtitle', 'Subtitle');
		$categoriesTable->addLinkButton('category-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$categoriesTable->addLinkButton('product-profileRemoveCategory/' . $id . '/?', 'fa-solid fa-square-minus', 'Remove Category');
		$categoriesTable = $categoriesTable->render();

		// Images
		$primaryImage = DB::select('SELECT
			a.fileName
			FROM product_images AS pi
			INNER JOIN asset AS a ON a.id = pi.assetId
			WHERE pi.productId = ?
			AND pi.primary = 1
			LIMIT 1',
			[$id]
		);

		if (!empty($primaryImage)) {
			$primaryImage = cacheImage($primaryImage[0]->fileName, 1200, 1200);
		}

		$imagesForm = new DataForm(request(), sprintf('/product-profileAddImage/%d', $id), 'Add Image');
		$imagesForm->addInput('file', 'image', 'Image', null, null, null, true, null, ['multiple']);
		$imagesForm = $imagesForm->render();

		$imagesTable = new DataTable('product_images');
		$imagesTable->sequence('productId');
		$imagesTable->setQuery('SELECT 
			pi.id,
			a.name,
			pi.primary,
			pi.active,
			pi.sequence,
			a.fileName
			FROM product_images AS pi
			LEFT JOIN asset AS a ON a.id = pi.assetId
			WHERE pi.productId = ?',
			[$id]
		);
		$imagesTable->addColumn('id', '#');
		$imagesTable->addColumn('name', 'Title', 2);
		$imagesTable->addColumn('primary', 'Primary', 1, false, 'setPrimary:productId:' . $id);
		$imagesTable->addColumn('active', 'Active', 1, false, 'toggle');
		$imagesTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$imagesTable->addJsButton('showDeleteWarning', ['string:Image', 'record:id', 'url:/product-profileDeleteImage/?'], 'fa-solid fa-trash-can', 'Delete Image');
		$imagesTable = $imagesTable->render();

		$specsForm = new DataForm(request(), sprintf('/product-profileAddSpec/%d', $id), 'Add Specification');
		$specsForm->addInput('text', 'label', 'Label', null, 255, 1);
		$specsForm->addInput('text', 'value', 'Value', null, 255, 1, true);
		// $specsForm->addInput('textarea', 'description', 'Description', null, 1000);
		$specsForm = $specsForm->render();

		$specsTable = new DataTable('product_spec');
		$specsTable->sequence('productId');
		$specsTable->setQuery('SELECT 
			ps.*
			FROM product_spec AS ps
			WHERE ps.productId = ?',
			[$id]
		);
		$specsTable->addColumn('id', '#');
		$specsTable->addColumn('label', 'Label');
		$specsTable->addColumn('value', 'Value');
		$specsTable->addColumn('active', 'Active', 1, false, 'toggle');
		$specsTable->addJsButton('showDeleteWarning', ['string:Specification', 'record:id', 'url:/product-profileDeleteSpec/?'], 'fa-solid fa-trash-can', 'Delete Specification');
		$specsTable = $specsTable->render();

		//Stock
		$stockForm = new DataForm(request(), sprintf('/product-profileAddStock/%d', $id), 'Add Stock');
		$stockForm->addInput('num', 'quantity', 'Quantity', null, 999, -999, true);
		$stockForm = $stockForm->render();

		$stockTable = new DataTable('product_stock');
		$stockTable->setQuery('SELECT 
			ps.*,
			CONCAT(u.firstName, " ", u.lastName) AS user
			FROM product_stock AS ps
			INNER JOIN users AS u ON u.id = ps.userId
			WHERE ps.productId = ?',
			[$id],
			'id', 
			'DESC'
		);
		$stockTable->addColumn('id', '#');
		$stockTable->addColumn('quantity', 'Quantity', 1);
		$stockTable->addColumn('user', 'User', 2);
		$stockTable->addColumn('created_at', 'Created At', 2);
		$stockTable = $stockTable->render();

		// Variants
		$allVariants = DB::select('SELECT
			pv.id AS value,
			pv.title AS label,
			parent.title AS parent,
			IF(pvj.id IS NOT NULL, true, false) AS `active`
			FROM product_variants AS pv
			INNER JOIN product_variants AS parent ON parent.id=pv.parentVariantId
			LEFT JOIN product_variant_joins AS pvj ON pvj.variantId = pv.id AND pvj.productId = ?
			WHERE pv.active = 1
			AND parent.active = 1
			GROUP BY pv.id
			ORDER BY parent.title, pv.title', 
			[$id]
		);

		$variantsForm = new DataForm(request(), sprintf('/product-profileAddVariant/%d', $id), 'Add Variant');
		$variantsForm->addInput('select', 'variant', 'Variant', null, null, null, true);
		$variantsForm->populateOptions('variant', $allVariants);
		$variantsForm = $variantsForm->render();

		$variantsTable = new DataTable('product_variants');
		$variantsTable->setQuery('SELECT
			pv.*,
			pv2.title AS parent
			FROM product_variant_joins AS pvj
			INNER JOIN product_variants AS pv ON pv.id=pvj.variantId
			INNER JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId
			WHERE pvj.productId = ?', 
			[$id],
			'parent, title'
		);
		$variantsTable->addColumn('id', '#');
		$variantsTable->addColumn('title', 'Title');
		$variantsTable->addColumn('parent', 'Type');
		// $variantsTable->addLinkButton('variant-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$variantsTable->addLinkButton('product-profileRemoveVariant/' . $id . '/?', 'fa-solid fa-square-minus', 'Remove Variant');
		$variantsTable = $variantsTable->render();

    return view('/admin/product-profile', compact(
      'product',
      'primaryImage',
			'editForm',
			'categoryForm',
      'categoriesTable',
			'imagesForm',
      'imagesTable',
			'specsForm',
			'specsTable',
			'stockForm',
			'stockTable',
			'variantsForm',
      'variantsTable',
    ));
  }

	public function toggleProduct(int $product, int $toggle) {
		Products::find($product)->update([
      'active' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/product-profile/" . $product)->with('message', "Product is now on.");
    } else {
      return redirect("/admin/product-profile/" . $product)->with('message', "Product is now off.");
    }
	}

  public function update(Request $request, $id)
  {
		$request->validate([
      'title' => 'required|max:100',
      'subtitle' => 'max:255',
      'description' => 'max:5000',
      'productnumber' => ['nullable', 'max:100', Rule::unique('products')->ignore($id)],
			'orbitalVisionId' => 'max:100',
      'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
			'maxQuantity' => 'numeric|nullable',
    ]);

		if ($request->orbitalVisionId == '') {
			$request->orbitalVisionId = null;
		}

		$startDate = null;
		$endDate = null;

		if (!is_null($request->startDate) ) {
			$startDate = date('Y-m-d H:i:s', strtotime($request->startDate));
		}

		if (!is_null($request->endDate)) {
			$endDate = date('Y-m-d H:i:s', strtotime($request->endDate));
		}

    Products::where('id', $id)->update([
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'description' => $request->description,
      'productnumber' => $request->productnumber,
			'orbitalVisionId' => $request->orbitalVisionId,
      'price' => $request->price,
			'maxQuantity' => $request->maxQuantity,
			'startDate' => $startDate,
			'endDate' => $endDate,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Product updated.');
  }


  public function delete($id)
  {
    Products::find($id)->delete();

    return redirect("/admin/products")->with('message', 'Product deleted.');
  }

	
	function clearCache($id) {
		Cache::forget("public-page-product-" . $id);

		return redirect("/admin/product-profile/" . $id)->with('message', 'Recached successfully.');
	}


  public function addImage(Request $request, $id)
  {
		$fileNames = storeImages($request, $id, 'product');

		foreach ($fileNames as $fileName) {
			ProductImages::create([
				'productId' => $id,
				// 'name' => !empty($request->name) ? $request->name : $fileName['old'],
				'assetId' => $fileName['id'],
				'primary' => 0,
			]);
		}

    return redirect("/admin/product-profile/$id")->with('message', 'Image uploaded.');
  }

  public function deleteImage($imageId)
  {
    $id = ProductImages::where('id', $imageId)->pluck('productId')->first();
    
		$fileName = DB::select('SELECT
			a.fileName
			FROM product_images AS pci
			INNER JOIN asset AS a ON a.id = pci.assetId
			WHERE pci.id = ?
			LIMIT 1',
			[$imageId]
		);

    ProductImages::where('id', $imageId)->delete();

    return redirect("/admin/product-profile/$id")->with('message', "Image #$imageId has been deleted.");
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

  public function addCategory(Request $request, int $id)
  {
    $request->validate([
      'category' => 'required',
    ]);

    ProductCategoryJoins::updateOrCreate([
      'productId' => $id,
      'categoryId' => $request->category,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Category added.');
  }

  public function removeCategory($id, $categoryId)
  {
    ProductCategoryJoins::where('productId', $id)->where('categoryId', $categoryId)->delete();

    return redirect("/admin/product-profile/$id")->with('message', "Category #$categoryId has been removed.");
  }

	public function addSpec(Request $request, int $id)
	{
		$request->validate([
			'label' => 'max:255',
			'value' => 'required|max:255',
		]);

		ProductSpec::create([
			'productId' => $id,
			'label' => $request->label,
			'value' => $request->value,
			// 'description' => $request->description,
		]);

		return redirect("/admin/product-profile/$id")->with('message', 'Specification added.');
	}

	public function deleteSpec($specId)
	{
		$id = ProductSpec::where('id', $specId)->pluck('productId')->first();

		ProductSpec::where('id', $specId)->delete();

		return redirect("/admin/product-profile/$id")->with('message', "Specification #'$specId' has been deleted.");
	}

	public function addStock(Request $request, int $id)
	{
		$request->validate([
			'quantity' => 'required|numeric',
		]);

		ProductStock::create([
			'productId' => $id,
			'quantity' => $request->quantity,
			'userId' => auth()->user()->id,
		]);

		$product = Products::find($id);
		$product->stock += $request->quantity;
		$product->save();

		return redirect("/admin/product-profile/$id")->with('message', 'Stock added.');
	}

  public function addVariant(Request $request, int $id)
  {
    $request->validate([
      'variant' => 'required',
    ]);

    ProductVariantJoins::updateOrCreate([
      'productId' => $id,
      'variantId' => $request->variant,
    ]);

    return redirect("/admin/product-profile/$id")->with('message', 'Variant added.');
  }

  public function removeVariant($id, $variantId)
  {
    ProductVariantJoins::where('productId', $id)->where('variantId', $variantId)->delete();

    return redirect("/admin/product-profile/$id")->with('message', "Variant #$variantId has been removed.");
  }
}
