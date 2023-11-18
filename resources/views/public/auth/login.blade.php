@extends('layout')

@section('title', 'Login')

@section('content')
  <main class="auth" id="loginPage">

    <h1 class="dk">Login</h1>

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

    <div id="publiclogin">
      <publiclogin />
    </div>

    <div class="auth-nav dk">
      <p>Don't have an account?</p>
      <a href="/sign-up"><button class="page-button" type="button" name="button">Sign Up</button></a>
    </div>


  </main>
@endsection
