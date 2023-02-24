<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailCustomer;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class AuthController extends Controller
{
  public function authenticateAdmin(Request $request)
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

      return redirect()->intended('/admin/dashboard')->with('message', 'Signed in successfully.');
    }

    return redirect("/admin")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logoutAdmin() {
    Session::flush();
    Auth::logout();

    return Redirect('/admin')->with('message', 'Logged out successfully.');
  }



  public function veiwLogin()
  {
    $sessionUser = auth()->user();

    return view('public/auth/login', compact(
      'sessionUser',
    ));
  }

  public function veiwLoginCart()
  {
    return redirect("/login")->with('message', 'Please login before adding items to the cart.');
  }

  public function authenticateCustomer(Request $request)
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

    if (Auth::attempt($credentials) && $admin[0]->admin == 0) {
      $request->session()->regenerate();

      return redirect()->intended('/')->with('message', 'Signed in successfully.');
    }

    return redirect("/login")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logoutCustomer() {
    Session::flush();
    Auth::logout();

    return Redirect('/')->with('message', 'Logged out successfully.');
  }


  public function veiwSignup()
  {
    $sessionUser = auth()->user();

    return view('public/auth/signup', compact(
      'sessionUser',
    ));
  }

  public function signupCustomer(Request $request)
  {
    $request->validate([
      'firstname' => 'required|max:100',
      'lastname' => 'required|max:100',
      'email' => ['required', 'email', 'max:100', Rule::unique('users')],
      'password' => 'required|confirmed|min:6|max:100'
    ]);

    $user = User::create([
      'admin' => 0,
      'firstname' => ucfirst($request->firstname),
      'lastname' => ucfirst($request->lastname),
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    return redirect('/verification.verify/' . $user->id);
  }

  public function viewVerifyEmailCustomer($id)
  {
    $sessionUser = auth()->user();

    $email = User::select('email')->where('id', $id)->first();

    $email = $email->email;

    return view('public/auth/verification.verify', compact(
      'sessionUser',
      'id',
      'email',
    ));
  }

  public function resendVerifyEmailCustomer($id)
  {
    $email = User::select('email')->where('id', $id)->first();

    $email = $email->email;

    Mail::to($email)->send(new VerifyEmailCustomer());

    return redirect('/verification.verify/' . $id)->with('message', 'Verification email sent again.');
  }

  public function emailVerifiedCustomer($id)
  {
    return redirect('/login')->with('message', 'Email verified successfully.');
  }
}
