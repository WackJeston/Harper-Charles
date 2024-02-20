<?php
namespace App\Http\Controllers;

Use DB;

class PublicController extends Controller
{
  public function __construct()
	{
		$sessionUser = auth()->user();

		$basketCount = 0;

		if ($sessionUser != null) {
			$basketCountData = DB::select('SELECT
				o.*
				FROM orders AS o
				WHERE o.status = "basket" 
				AND o.userId = 3
				LIMIT 1'
			);

			if (!empty($basketCountData)) {
				$basketCount = $basketCountData[0]->items;
			}
		}

		view()->share('sessionUser', $sessionUser);
		view()->share('basketCount', $basketCount);
	}
}
