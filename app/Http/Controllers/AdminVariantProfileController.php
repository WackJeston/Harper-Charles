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
    $sessionUser = auth()->user();

    if (ProductVariants::find($id) == null) {
      return redirect('/admin/variants');
    }

		// Vatiant
    $variant = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
      COUNT(pv2.id) AS childrenCount,
      GROUP_CONCAT(pv2.id) AS children,
      pv.show
      FROM product_variants AS pv
      LEFT JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
      WHERE pv.id = "%d"
      GROUP BY pv.id
      LIMIT 1
    ', $id));

    $variant = $variant[0];

		// Edit
		$editForm = new DataForm(request(), sprintf('/variant-profileUpdate/%d', $id), 'Update Variant');
		$editForm->addInput('text', 'title', 'Title', $variant->title, 100, 1, true);
		$editForm = $editForm->render();

		// Sub Variants
		$subVariantsForm = new DataForm(request(), sprintf('/variant-profileCreateOption/%d', $id), 'Add Option');
		$subVariantsForm->addInput('text', 'title', 'Option Title', null, 100, 1, true);
		$subVariantsForm = $subVariantsForm->render();

		$subVariantsTable = new DataTable('product_variants');
		$subVariantsTable->setQuery('SELECT
			pv.*
			FROM product_variants AS pv
			WHERE pv.parentVariantId = ?', [$id]
		);
		$subVariantsTable->addColumn('id', '#');
		$subVariantsTable->addColumn('title', 'Title', 2);
		$subVariantsTable->addColumn('show', 'Active', 1, false, 'toggle');
		$subVariantsTable->addJsButton('showDeleteWarning', ['string:Variant', 'record:id', 'url:/variant-profileDeleteOption/' . $id . '/?'], 'fa-solid fa-trash-can', 'Delete Variant');
		$subVariantsTable = $subVariantsTable->render();

    return view('admin/variant-profile', compact(
      'sessionUser',
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
      'show' => $toggle,
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

    ProductVariants::create([
      'parentVariantId' => $id,
      'title' => $request->title,
      'show' => 0,
    ]);

    return redirect("/admin/variant-profile/$id")->with('message', 'Option added.');
  }

  public function deleteOption($id, $optionId)
  {
    ProductVariants::find($optionId)->delete();

    return redirect("/admin/variant-profile/" . $id)->with('message', 'Option deleted.');
  }
}
