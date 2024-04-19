@extends('body-public')

@section('title', 'Sign Up')

@section('content')
  <main class="auth dk" id="signupPage">

    <h1>Sign Up</h1>

    @if ($errors->any())
      <div id="publicerror" class="lt limited">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt limited">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="publicsignup">
      <publicsignup 
				:appname="{{ json_encode(ENV('APP_NAME')) }}"
			/>
    </div>

    <div class="auth-nav">
      <p>Already have an account?</p>
      <a href="/login"><button class="page-button padding" type="button" name="button">login</button></a>
    </div>

  </main>
@endsection
