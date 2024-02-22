<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return string|null
   */
  protected function redirectTo($request)
  {
    if (! $request->expectsJson()) {
      return route('login');
    }
  }


  public function handle($request, Closure $next, ...$guards)
  {
    if (str_ends_with(url()->current(), '/admin')) {
      if (Auth::check() && auth()->user()['admin'] == 1) {
				if ($request->ajax()) {
          return response('Unauthorized.', 401);
        } else {
          return redirect("/admin/dashboard");
        }
      }

    } elseif(str_contains(url()->current(), '/admin/')){
      if (!Auth::check() || auth()->user()['admin'] == 0) {
        if ($request->ajax()) {
          return response('Unauthorized.', 401);
        } else {
          return redirect("/admin");
        }
      }

    } elseif (!str_contains(url()->current(), '/admin') && !str_contains(url()->current(), '/verify-email') && !Auth::check()) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				return redirect("/login")->withErrors(['1' => 'Please login before viewing this page.']);
			}
		}

    return $next($request);
  }
}
