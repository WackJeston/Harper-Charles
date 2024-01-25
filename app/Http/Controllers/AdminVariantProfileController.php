<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\DataTable;
use App\DataForm;
use App\Models\Products;
use App\Models\ProductVariants;

class AdminVariantProfileController extends Controller
{
  public function show($id)
  {
    if (ProductVariants::find($id) == null) {
      return redirect('/admin/variants')->withErrors(['1' => 'Variant not found']);
    }

		// Vatiant
    $variant = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
			pv.type,
      COUNT(pv2.id) AS childrenCount,
      GROUP_CONCAT(pv2.id) AS children,
      pv.active
      FROM product_variants AS pv
      LEFT JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
      WHERE pv.id = "%d"
      GROUP BY pv.id
      LIMIT 1
    ', $id));

    $variant = $variant[0];

		$variantTypes = [
			['label' => 'Text', 'value' => 'text'],
			['label' => 'Image', 'value' => 'image'],
			['label' => 'Colour', 'value' => 'colour'],
		];

		// Edit
		$editForm = new DataForm(request(), sprintf('/variant-profileUpdate/%d', $id), 'Update Variant');
		$editForm->addInput('text', 'title', 'Title', $variant->title, 100, 1, true);
		$editForm->addInput('radio', 'type', 'Type', $variant->type, 100, 1, true);
		$editForm->populateOptions('type', $variantTypes);
		$editForm = $editForm->render();

		// Sub Variants
		$subVariantsForm = new DataForm(request(), sprintf('/variant-profileCreateOption/%d', $id), 'Add Option');
		$subVariantsForm->addInput('text', 'title', 'Option Title', null, 100, 1, true);
		$subVariantsForm->addInput('file', 'image', 'Image', null, null, null, false, null, ['multiple']);
		$subVariantsForm->addInput('colour', 'colour', 'Colour');
		$subVariantsForm = $subVariantsForm->render();

		$subVariantsTable = new DataTable('product_variants');
		$subVariantsTable->setQuery('SELECT
			pv.*,
			a.fileName
			FROM product_variants AS pv
			LEFT JOIN asset AS a ON a.id = pv.assetId
			WHERE pv.parentVariantId = ?', [$id]
		);
		$subVariantsTable->addColumn('id', '#');
		$subVariantsTable->addColumn('title', 'Title', 2);
		if ($variant->type == 'colour') {
			$subVariantsTable->addColumn('colour', 'Colour', 1);
		}
		$subVariantsTable->addColumn('active', 'Active', 1, false, 'toggle');
		if ($variant->type == 'image') {
			$subVariantsTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		}
		$subVariantsTable->addJsButton('showDeleteWarning', ['string:Variant', 'record:id', 'url:/variant-profileDeleteOption/' . $id . '/?'], 'fa-solid fa-trash-can', 'Delete Variant');
		$subVariantsTable = $subVariantsTable->render();

    return view('admin/variant-profile', compact(
      'variant',
			'editForm',
			'subVariantsForm',
      'subVariantsTable',
    ));
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|max:100',
    ]);

    ProductVariants::where('id', $id)->update([
      'title' => $request->title,
			'type' => $request->type,
    ]);

    return redirect("/admin/variant-profile/$id")->with('message', 'Variant updated.');
  }


  public function delete($id)
  {
    ProductVariants::find($id)->delete();

    return redirect("/admin/variants")->with('message', 'Variant deleted.');
  }


  public function showVariant($variant, $toggle)
  {
    ProductVariants::find($variant)->update([
      'active' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/variant-profile/" . $variant)->with('message', "Variant is now on.");
    } else {
      return redirect("/admin/variant-profile/" . $variant)->with('message', "Variant is now off.");
    }
  }


  public function createOption(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|max:100',
    ]);

		$option = new ProductVariants;

		$option->parentVariantId = $id;
		$option->title = $request->title;
		$option->colour = $request->colour;
		$option->active = 0;

		if (count($request->files) > 0) {
			$fileNames = storeImages($request, $id, 'product')[0];
			$option->assetId = $fileNames['id'];
		}

		$option->save();

    return redirect("/admin/variant-profile/$id")->with('message', 'Option added.');
  }

  public function deleteOption($id, $optionId)
  {
    ProductVariants::find($optionId)->delete();

    return redirect("/admin/variant-profile/" . $id)->with('message', 'Option deleted.');
  }
}
