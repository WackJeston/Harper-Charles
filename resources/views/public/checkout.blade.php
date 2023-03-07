@extends('layout')

@section('title', 'Checkout')

@section('content')
  <main class="auth dk" id="checkout-page">

    <h1>Checkout</h1>

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

    <div id="checkout-timeline">
      <i class="fa-regular fa-circle"></i>
      <div></div>
      <i class="fa-regular fa-circle"></i>
      <div></div>
      <i class="fa-regular fa-circle"></i>
    </div>

    <div id="checkout" class="checkout-main web-box">
      <checkout />
    </div>

  </main>
@endsection