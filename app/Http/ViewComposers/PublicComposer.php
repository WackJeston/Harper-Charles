<?php
 
namespace App\Http\ViewComposers;
 
use DB;
use Illuminate\View\View;
 
class PublicComposer
{
	protected $sessionUser;

	public function __construct()
	{
		$this->sessionUser = auth()->user();

		$this->basketCount = 0;

		if ($this->sessionUser != null) {
			$basketCountData = DB::select('SELECT
				o.*
				FROM orders AS o
				WHERE o.status = "basket" 
				AND o.userId = 3
				LIMIT 1'
			);

			if (!empty($basketCountData)) {
				$this->basketCount = $basketCountData[0]->items;
			}
		}
	}

	public function compose(View $view)
	{
		$view->with('sessionUser', $this->sessionUser);
	}
}