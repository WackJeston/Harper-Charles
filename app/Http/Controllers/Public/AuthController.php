<?php

namespace App\Http\Controllers\Public;

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

class AuthController extends PublicController
{
  public function show()
  {
    return view('public/auth/login');
  }

  public function basketRedirect()
  {
    return redirect("/login")->withErrors(['1' => 'Please login before adding items to your basket.']);
  }

  public function authenticate(Request $request)
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

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return redirect('/')->with('message', 'Signed in.');
    }

    return redirect("/login")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logout() {
    Session::flush();
    Auth::logout();

    return Redirect('/')->with('message', 'Logged out.');
  }


  public function showSignup()
  {
    return view('public/auth/signup');
  }

  public function signup(Request $request)
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
		
		if (env('KLAVIYO_ENABLED') && $request->marketing == 'on') {
			subscribeKlaviyo($user->id);
		}

		$credentials = $request->only('email', 'password');
		Auth::attempt($credentials);

		$request->session()->regenerate();

		return redirect('/')->with('message', 'Signed up successfully.');
  }

  public function showVerifyEmail($id)
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

  public function resendVerifyEmail($id)
  {
    $user = User::where('id', $id)->first();

    $user->sendEmailVerificationNotification();

    $email = $user->email;

    return redirect('/verify-email/' . $id)->with('message', 'Verification email sent again.');
  }

  public function showEmailVerified($id)
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
