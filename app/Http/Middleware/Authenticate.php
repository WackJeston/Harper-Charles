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

  if(str_contains(url()->current(), '/admin/')){
    if (!Auth::check() || auth()->user()['admin'] == 0) {
      return redirect("/admin")->withErrors(['email' => 'Access Denied.']);
    }
  }

  // elseif (str_contains(url()->current(), '/admin')) {
  //   if (Auth::check()) {
  //     return redirect("/admin/dashboard");
  //   }
  // }

    return $next($request);
}
}
