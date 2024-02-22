<?php
namespace App\Http\Controllers;

Use DB;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
  public function __construct()
	{
		// $this->middleware(function ($request, $next) {
		// 	$this->sessionUser= Auth::user();

		// 	return $next($request);
		// });

		// dd($this->sessionUser);

		// $basketCount = 0;

		// if (auth()->user() != null) {
		// 	$basketCountData = DB::select('SELECT
		// 		o.*
		// 		FROM orders AS o
		// 		WHERE o.status = "basket" 
		// 		AND o.userId = 3
		// 		LIMIT 1'
		// 	);

		// 	if (!empty($basketCountData)) {
		// 		$basketCount = $basketCountData[0]->items;
		// 	}
		// }

		// view()->share('basketCount', $basketCount);
	}
}
