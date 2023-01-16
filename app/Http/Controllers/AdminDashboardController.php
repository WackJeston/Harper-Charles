<?php

namespace App\Http\Controllers;


class AdminDashboardController extends Controller
{
  public function show()
  {
    $sessionUser = auth()->user();

    return view('admin/dashboard', compact(
      'sessionUser',
    ));
  }
}
