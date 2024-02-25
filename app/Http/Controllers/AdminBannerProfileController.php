<?php

namespace App\Http\Controllers;

use App\Models\Banners;


class AdminBannerProfileController extends AdminController
{
  public function show($id)
  {
		if (!$banner = Banners::find($id)) {
      return redirect('/admin/banners')->withErrors(['1' => 'Banner not found']);
    }

		$framingOptions = [];
		$framingOptions[] = ['value' => null, 'label' => 'Center'];
		$framingOptions[] = ['value' => 'top', 'label' => 'Top'];
		$framingOptions[] = ['value' => 'bottom', 'label' => 'Bottom'];

    $slidesTable = new dataTable('banners');
		$slidesTable->sequence('parentId');
		$slidesTable->setQuery('SELECT
			b.id,
			b.title,
			b.description,
			b.framing,
			b.active,
			a.fileName
			FROM banners AS b
			LEFT JOIN asset AS a ON a.id = b.assetId
			WHERE b.parentId = ?
			GROUP BY b.id',
			[$id]
		);
		$slidesTable->addColumn('id', '#');
		$slidesTable->addColumn('title', 'Title', 2);
		$slidesTable->addColumn('description', 'Subtitle', 2, false, 'text');
		$slidesTable->addColumn('framing', 'Framing', 1, true, 'select', $framingOptions);
		$slidesTable->addColumn('active', 'Active', 1, false, 'toggle');
		$slidesTable->addJsButton('showImage', ['record:fileName'], 'fa-solid fa-eye', 'View Image');
		$slidesTable->addJsButton('showDeleteWarning', ['string:Slide', 'record:id', 'url:/banner-profileDeleteSlide/?'], 'fa-solid fa-trash-can', 'Delete Image');
		$slidesTable = $slidesTable->render();

		$slideForm = new dataForm(request(), sprintf('/banner-profileAddSlide/%d', $id), 'Add');
		$slideForm->addInput('text', 'title', 'Title', null, 100, 1);
		$slideForm->addInput('text', 'description', 'Subtitle', null, 100, 1);
		$slideForm->addInput('file', 'image', 'Image', null, 100, 1, true);
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
				'assetId' => $fileName['id'],
			]);
		}

    return redirect("/admin/banner-profile/$id")->with('message', 'Slide Added.');
  }

	public function deleteSlide($id) {
		$slide = Banners::find($id);
		$parentId = $slide->parentId;
		$slide->delete();

		return redirect("/admin/banner-profile/$parentId")->with('message', 'Slide Deleted.');
	}
}
