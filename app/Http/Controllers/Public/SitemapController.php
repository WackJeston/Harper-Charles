<?php
namespace App\Http\Controllers\Public;

use DB;
use Illuminate\Http\Request;

class SitemapController extends PublicController
{
  public function show()
  {
    return view('public/site-map');
  }
}
