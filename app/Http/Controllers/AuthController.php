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

      return redirect()->intended('/admin/dashboard')->with('message', 'Signed in.');
    }

    return redirect("/admin")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logoutAdmin() {
    Session::flush();
    Auth::logout();

    return Redirect('/admin')->with('message', 'Logged out.');
  }



  public function veiwLogin()
  {
    return view('public/auth/login');
  }

  public function veiwLoginCart()
  {
    return redirect("/login")->withErrors(['1' => 'Please login before adding items your cart.']);
  }

  public function authenticateCustomer(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
    ]);

    $customer = DB::select(sprintf('SELECT
      u.id,
      u.admin,
      u.email_verified_at
      FROM users AS u
      WHERE u.email = "%s"
      LIMIT 1
    ', $request->email));

    if (empty($customer)) {
      return redirect("/login")->withErrors([
        'email' => 'The provided credentials do not match our records.',
      ]);
    }
    
    // if ($customer[0]->email_verified_at == null || $customer[0]->email_verified_at == '') {
    //   return redirect('/verify-email/' . $customer[0]->id);
    // }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials) && $customer[0]->admin == 0) {
      $request->session()->regenerate();

      return redirect()->intended('/')->with('message', 'Signed in.');
    }

    return redirect("/login")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logoutCustomer() {
    Session::flush();
    Auth::logout();

    return Redirect('/')->with('message', 'Logged out.');
  }


  public function veiwSignup()
  {
    return view('public/auth/signup');
  }

  public function signupCustomer(Request $request)
  {
		$user = User::where('email', $request->email)->first();

		// if (!empty($user)) {
		// 	if ($user->email_verified_at == null) {
		// 		$user->sendEmailVerificationNotification();
		// 		return redirect('/verify-email/' . $user->id)->with('message', 'An account with this email already exists.');
		// 	}
		// }

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

		$options = [
			'email' => $user->email,
			'name' => $user->firstname . ' ' . $user->lastname,
			'metadata' => [
				'id' => $user->id,
			],
		];

		$stripeUser = $user->createAsStripeCustomer($options);
    // event(new Registered($user));

    // return redirect('/verify-email/' . $user->id)->with('message', 'Signed in.');
    return redirect('/')->with('message', 'Signed up successfully.');
  }

  public function viewVerifyEmailCustomer($id)
  {
    $user = User::where('id', $id)->first();

    if ($user->email_verified_at != null || $user->email_verified_at != '') {
      return redirect('/login')->with('message', 'Email already verified.');
    }

    $email = $user->email;

    return view('public/auth/verify-email', compact(
      'id',
      'email',
    ));
  }

  public function resendVerifyEmailCustomer($id)
  {
    $user = User::where('id', $id)->first();

    $user->sendEmailVerificationNotification();

    $email = $user->email;

    return redirect('/verify-email/' . $id)->with('message', 'Verification email sent again.');
  }

  public function emailVerifiedCustomer($id)
  {
		if ($user = User::where('id', $id)->first()) {
			if ($user->email_verified_at == null || $user->email_verified_at == '') {
				$user->email_verified_at = now();
				$user->save();
			}
	
			$email = $user->email;
	
			return view('public/auth/email-verified', compact(
				'email',
			));

		} else {
			return redirect('/login')->withErrors(['1' => 'Session expired.']);
		}
  }
}
