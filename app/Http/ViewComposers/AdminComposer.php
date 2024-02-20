<?php
 
namespace App\Http\ViewComposers;
 
use Illuminate\View\View;
 
class AdminComposer
{
	protected $sessionUser;

	public function __construct()
	{
		$this->sessionUser = auth()->user();
	}

	public function compose(View $view)
	{
		$view->with('sessionUser', $this->sessionUser);
	}
}