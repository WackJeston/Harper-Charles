<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class TestController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    return view('system/test', compact(
      'sessionUser',
    ));
  }
}
