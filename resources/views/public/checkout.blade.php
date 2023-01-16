@extends('layout')

@section('title', 'Checkout')

@section('content')
  <main class="auth lt" id="checkoutPage">

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

    <div id="checkout">
      <checkout />
    </div>

  </main>
@endsection