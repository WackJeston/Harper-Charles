@extends('body-admin-login')

@section('title', 'Login')

@section('content')
  <main class="login" id="adminLoginPage">

    <h1>Admin Login</h1>

    @if ($errors->any())
      <div id="alerterror" class="lt">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="adminlogin">
      <adminlogin />
    </div>

    <h2 class="dk">{{ strtoupper(env('APP_NAME')) }}</h2>

  </main>
@endsection
