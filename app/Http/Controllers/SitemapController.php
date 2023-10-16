<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
  public function show()
  {
    return view('public/site-map');
  }
}
