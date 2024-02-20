<?php
 
namespace App\Providers;
 
use Illuminate\Support\ServiceProvider;
 
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
			// Using class based composers...
			// view()->creator(
			// 		[
			// 			'layout'
			// 		], 
			// 		'App\Http\ViewComposers\PublicComposer'
			// );
    }
 
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
			if (str_contains(url()->current(), '/admin')) {
				view()->composer(
					[
						'layout'
					], 
					'App\Http\ViewComposers\AdminComposer'
				);

			} else {
				view()->composer(
					[
						'layout'
					], 
					'App\Http\ViewComposers\PublicComposer'
				);
			}
    }
}