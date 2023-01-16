<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
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

    $subVariants = DB::select(sprintf('SELECT
      pv.id,
      pv.title,
      pv.show
      FROM product_variants AS pv
      WHERE pv.parentVariantId = "%d"
    ', $id));

    return view('admin/variant-profile', compact(
      'sessionUser',
      'variant',
      'subVariants',
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

    return redirect("/admin/variant-profile/$id")->with('message', 'Variant updated successfully.');
  }


  public function delete($id)
  {
    ProductVariants::find($id)->delete();

    return redirect("/admin/variants")->with('message', 'Variant deleted successfully.');
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
      'parentvariantid' => $id,
      'title' => $request->title,
      'show' => 0,
    ]);

    return redirect("/admin/variant-profile/$id")->with('message', 'Option added successfully.');
  }

  public function deleteOption($id, $optionId)
  {
    ProductVariants::find($optionId)->delete();

    return redirect("/admin/variant-profile/" . $id)->with('message', 'Option deleted successfully.');
  }

  public function showOption($id, $optionId, $toggle)
  {
    ProductVariants::find($optionId)->update([
      'show' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/variant-profile/" . $id)->with('message', "Option is now on.");
    } else {
      return redirect("/admin/variant-profile/" . $id)->with('message', "Option is now off.");
    }
  }
}
