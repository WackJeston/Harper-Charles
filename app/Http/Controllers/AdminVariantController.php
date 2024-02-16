<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\DataTable;
use App\DataForm;
use App\Models\ProductVariants;
use App\Models\Products;

class AdminVariantController extends Controller
{
  public function show()
  {
		$variantTypes = [
			['label' => 'Text', 'value' => 'text'],
			['label' => 'Image', 'value' => 'image'],
			['label' => 'Colour', 'value' => 'colour'],
		];

    $createForm = new DataForm(request(), '/variantCreate', 'Add');
		$createForm->addInput('text', 'title', 'Title', null, 255, 1, true);
		$createForm->addInput('radio', 'type', 'Type', 'text', 100, 1, true);
		$createForm->populateOptions('type', $variantTypes);
		$createForm = $createForm->render();

    $variantsTable = new DataTable('product_variants_REF_1');
		$variantsTable->setQuery('SELECT
			pv.id,
			pv.title,
			pv.type,
			pv.reference,
			COUNT(pv2.id) AS children,
			pv.active
			FROM product_variants AS pv
			LEFT JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
			WHERE pv.parentVariantId IS NULL
			GROUP BY pv.id
		');
		$variantsTable->addColumn('id', '#');
		$variantsTable->addColumn('title', 'Title', 3);
		$variantsTable->addColumn('type', 'Type', 2, false, 'select', $variantTypes);
		$variantsTable->addColumn('reference', 'Ref', 2, true);
		$variantsTable->addColumn('children', 'Children', 2);
		$variantsTable->addColumn('active', 'Show', 2, false, 'toggle');
		$variantsTable->addLinkButton('variant-profile/?', 'fa-solid fa-folder-open', 'Open Record');
		$variantsTable = $variantsTable->render();

    return view('admin/variants', compact(
			'createForm',
      'variantsTable',
    ));
  }


  public function showVariant($variant, $toggle)
  {
    ProductVariants::find($variant)->update([
      'active' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/variants")->with('message', "Variant is now on.");
    } else {
      return redirect("/admin/variants")->with('message', "Variant is now off.");
    }
  }


  public function create(Request $request)
  {
    $this->validate($request, [
      'title' => 'required|max:100',
    ]);

    ProductVariants::create([
      'title' => $request->title,
      'type' => $request->type,
      'active' => 0,
    ]);

    return redirect('/admin/variants')->with('message', 'Variant created.');
  }
}
