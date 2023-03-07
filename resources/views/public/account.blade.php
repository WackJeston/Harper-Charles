@extends('layout')

@section('title', 'Account')

@section('content')
  <main class="dk" id="account">

    <h2>Account</h2>

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

    <div id="accountfunctions">
      <accountfunctions :user="{{ $sessionUser }}" />
    </div>

    <div class="web-box dk">
      <p>{{ $sessionUser['firstName'] }} {{ $sessionUser['lastName'] }}</p>
      <p>{{ $sessionUser['email'] }}</p>
    </div>

  </main>
@endsection
