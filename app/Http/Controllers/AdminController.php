<?php
namespace App\Http\Controllers;

Use DB;


class AdminController extends Controller
{
  public function __construct()
	{
		$sessionUser = auth()->user();

		view()->share('sessionUser', $sessionUser);
	}
}
