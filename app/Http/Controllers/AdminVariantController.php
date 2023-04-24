<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\ProductVariants;
use App\Models\Products;

class AdminVariantController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $variants = DB::select('SELECT
      pv.id,
      pv.title,
      COUNT(pv2.id) AS children,
      pv.show
      FROM product_variants AS pv
      LEFT JOIN product_variants AS pv2 ON pv2.parentVariantId=pv.id
      WHERE pv.parentVariantId IS NULL
      GROUP BY pv.id
    ');

    return view('admin/variants', compact(
      'sessionUser',
      'variants',
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
