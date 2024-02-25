<?php

namespace App\Http\Controllers;


class SitemapController extends PublicController
{
  public function show()
  {
    return view('public/site-map');
  }
}
