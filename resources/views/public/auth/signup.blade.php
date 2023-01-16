@extends('layout')

@section('title', 'Sign Up')

@section('content')
  <main class="auth lt" id="signupPage">

    <h1>Sign Up</h1>

    @if ($errors->any())
      <div id="publicerror" class="lt">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="publicsignup">
      <publicsignup />
    </div>

    <div class="auth-nav">
      <p>Already have an account?</p>
      <a href="/login"><button class="page-button" type="button" name="button">login</button></a>
    </div>

  </main>
@endsection
