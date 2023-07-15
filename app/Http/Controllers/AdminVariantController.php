<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\dataTable;
use App\Models\ProductVariants;
use App\Models\Products;

class AdminVariantController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $variantsTable = new DataTable();
		
		$variantsTable->setQuery('SELECT
			pv.id,
			pv.title,
			COUNT(pv2.id) AS children,
			pv.show
			FROM product_variants AS pv
			LEFT JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
			WHERE pv.parentVariantId IS NULL
			GROUP BY pv.id
		');

		$variantsTable->addColumn('id', '#');
		$variantsTable->addColumn('title', 'Title', 2);
		$variantsTable->addColumn('children', 'Children');
		$variantsTable->addColumn('show', 'Show', 1, false, 'toggle');

		$variantsTable->addButton('variant-profile/?', 'fa-solid fa-folder-open', 'Open Record');

		$variantsTable = $variantsTable->display(true);

    return view('admin/variants', compact(
      'sessionUser',
      'variantsTable',
    ));
  }


  public function showVariant($variant, $toggle)
  {
    ProductVariants::find($variant)->update([
      'show' => $toggle,
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
      'show' => 0,
    ]);

    return redirect('/admin/variants')->with('message', 'Variant created successfully.');
  }
}
