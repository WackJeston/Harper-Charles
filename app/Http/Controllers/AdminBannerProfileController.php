<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTable;
use App\DataForm;
use App\Models\Banners;


class AdminBannerProfileController extends Controller
{
  public function show($id)
  {
		$banner = Banners::find($id);

		$framingOptions = [];
		$framingOptions[] = ['value' => null, 'label' => 'Center'];
		$framingOptions[] = ['value' => 'top', 'label' => 'Top'];
		$framingOptions[] = ['value' => 'bottom', 'label' => 'Bottom'];

    $slidesTable = new dataTable('banners');
		$slidesTable->setQuery('SELECT
			b.id,
			b.title,
			b.description,
			b.framing,
			b.active,
			b.fileName
			FROM banners AS b
			WHERE b.parentId = ?',
			[$id]
		);
		$slidesTable->addColumn('id', '#');
		$slidesTable->addColumn('title', 'Title', 2);
		$slidesTable->addColumn('description', 'Subtitle');
		$slidesTable->addColumn('framing', 'Framing', 1, true, 'select', $framingOptions);
		$slidesTable->addColumn('active', 'Active', 1, false, 'toggle');
		$slidesTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$slidesTable = $slidesTable->render();

		$slideForm = new dataForm(request(), sprintf('/banner-profileAddSlide/%d', $id), 'Add');
		$slideForm->addInput('text', 'title', 'Title', null, 100, 1);
		$slideForm->addInput('text', 'description', 'Subtitle', null, 100, 1);
		$slideForm->addInput('file', 'fileName', 'Image', null, 100, 1, true);
		$slideForm = $slideForm->render();

    return view('admin/banner-profile', compact(
			'banner',
			'slidesTable',
			'slideForm'
    ));
  }

	public function toggleBanner($banner, $toggle)
  {
    Banners::find($banner)->update([
      'active' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/banner-profile/" . $banner)->with('message', "Banner is now on.");
    } else {
      return redirect("/admin/banner-profile/" . $banner)->with('message', "Banner is now off.");
    }
  }

	public function addSlide(Request $request, $id)
  {
		$request->validate([
      'title' => 'max:100',
			'description' => 'max:100',
    ]);

		$fileNames = storeImages($request, $id, 'banner');

		foreach ($fileNames as $fileName) {
			Banners::create([
				'parentId' => $id,
				'title' => $request->title,
				'description' => $request->description,
				'name' => $fileName['old'],
				'fileName' => $fileName['new'],
			]);
		}

    return redirect("/admin/banner-profile/$id")->with('message', 'Slide Added.');
  }
}
