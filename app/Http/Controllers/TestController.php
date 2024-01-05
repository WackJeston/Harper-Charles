<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class TestController extends Controller
{
  public function show()
  {
    return view('system/test');
  }
}
