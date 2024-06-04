<?php

namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailCustomer;
use App\Models\User;

class AuthController extends AdminController
{
	public function show()
  {
    return view('admin/auth/login');
  }

  public function authenticate(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
    ]);

    $admin = DB::select(sprintf('SELECT
      u.admin
      FROM users AS u
      WHERE u.email = "%s"
      LIMIT 1
    ', $request->email));

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials) && $admin[0]->admin == 1) {
      $request->session()->regenerate();

      return redirect()->intended('/admin/dashboard')->with('message', 'Signed in.');
    }

    return redirect("/admin")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logout() {
    Session::flush();
    Auth::logout();

    return Redirect('/admin')->with('message', 'Logged out.');
  }
}
