@extends('body-public')

@section('title', 'Email Verified')

@section('content')
  <main class="auth dk" id="email-verified">

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

    <div class="web-box limited dk">
      <h3>Email Verified</h3>
      <p>{{ $email }} has been verified. You can now login.</p>
      <a href="/login" class="page-button padding">login</a>
    </div>

  </main>
@endsection
