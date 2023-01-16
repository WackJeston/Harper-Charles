<?php
namespace App\Http\Controllers;

Use DB;

class CheckoutController extends Controller
{
  public function show() 
  {
    $sessionUser = auth()->user();

    return view('public/checkout', compact(
      'sessionUser',
    ));
  }
}