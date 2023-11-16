<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Models\LandingZones;
use App\Models\LandingZoneCarousels;
use App\DataTable;
use App\DataForm;

class AdminLandingZonesController extends Controller
{
  public function show()
  {
    $table = new DataTable('landing_zones');

    $homepageCarousel = LandingZoneCarousels::where('landingZoneId', 1)->get();
    $homepageCarouselCount = count($homepageCarousel);

    $homepageCarouselShow = LandingZones::where('id', 1)->pluck('show')->first();

    if ($homepageCarouselCount == 1) {
      LandingZoneCarousels::where('landingZoneId', 1)->update([
        'primary' => 1,
      ]);
    }

    return view('/admin/landing-zones', compact(
      'homepageCarousel',
      'homepageCarouselCount',
      'homepageCarouselShow',
    ));
  }

  public function showZone($zone, $toggle)
  {
    LandingZones::where('id', $zone)->update([
      'show' => $toggle,
    ]);

    if ($toggle == 1) {
      return redirect("/admin/landing-zones")->with('message', "Landing zone is now on.");
    } else {
      return redirect("/admin/landing-zones")->with('message', "Landing zone is now off.");
    }
  }

  public function storeSlide(Request $request, $id)
  {
    $this->validate($request, [
      'title' => 'max:100',
      'subtitle' => 'max:100',
      'image' => 'required|mimes:jpg,jpeg,png,svg,webp',
    ]);

    if ($request->hasFile('image')) {
			$fileName = storeImages($request, $id, 'landingZone')[0]['new'];
    }

    LandingZoneCarousels::create([
      'landingZoneId' => $id,
      'title' => $request->title,
      'subtitle' => $request->subtitle,
      'fileName' => $fileName,
      'primary' => 0,
    ]);

    return redirect("/admin/landing-zones")->with('message', 'Slide uploaded successfully.');
  }

  public function deleteSlide($slideId)
  {
    $fileName = LandingZoneCarousels::where('id', $slideId)->pluck('fileName')->first();

    Storage::delete($fileName);

    LandingZoneCarousels::where('id', $slideId)->delete();

    $homepageCarouselCount = count(LandingZoneCarousels::where('landingZoneId', 1)->get());

    if ($homepageCarouselCount == 1) {
      LandingZoneCarousels::where('landingZoneId', 1)->update([
        'primary' => 1,
      ]);
    }

    return redirect("/admin/landing-zones")->with('message', "Slide #$slideId has been deleted.");
  }

  public function primarySlide($slideId)
  {
    LandingZoneCarousels::where('landingZoneId', 1)->update([
      'primary' => 0,
    ]);

    LandingZoneCarousels::where('id', $slideId)->update([
      'primary' => 1,
    ]);

    return redirect("/admin/landing-zones")->with('message', "Slide #$slideId is now the primary slide.");
  }
}
