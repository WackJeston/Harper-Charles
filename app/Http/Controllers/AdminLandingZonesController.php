<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Models\LandingZones;
use App\Models\LandingZoneCarousels;

class AdminLandingZonesController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    $homepageCarousel = LandingZoneCarousels::where('landingZoneId', 1)->get();
    $homepageCarouselCount = count($homepageCarousel);

    $homepageCarouselShow = LandingZones::where('id', 1)->pluck('show')->first();

    if ($homepageCarouselCount == 1) {
      LandingZoneCarousels::where('landingZoneId', 1)->update([
        'primary' => 1,
      ]);
    }

    return view('/admin/landing-zones', compact(
      'sessionUser',
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
      'image' => 'required|mimes:jpg,jpeg,png,svg',
    ]);

    $mimeType = str_replace('image/', '', $request->file('image')->getClientMimeType());
    if ($mimeType == 'svg+xml') { $mimeType = 'svg'; }
    else if ($mimeType == 'jpeg') { $mimeType = 'jpg'; }
    $fileName = 'landing-zone-' . $id . '-' . $_SERVER['REQUEST_TIME'] . '.' . $mimeType;
    

    if ($request->hasFile('image')) {
      $request->file('image')->move('assets', $fileName);

      uploadS3($fileName);
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

    deleteS3($fileName);

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
