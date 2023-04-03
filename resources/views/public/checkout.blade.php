@extends('layout')

@section('title', 'Checkout')

@section('content')
  <main class="auth" id="checkout-page">

    <h1 class="title dk">Checkout</h1>

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
      <i class="fa-solid fa-circle"></i>
      <div></div>
      <i class="fa-regular fa-circle"></i>
      <div></div>
      <i class="fa-regular fa-circle"></i>
    </div>

    <div id="checkoutaddresses" class="web-box dk">
      <checkoutaddresses 
				:deliveryaddressespre="{{ $deliveryAddresses }}" 
				:defaultdelivery="{{ $defaultDelivery }}" 
				:billingaddressespre="{{ $billingAddresses }}" 
				:defaultbilling="{{ $defaultBilling }}" 
			/>
    </div>

  </main>
@endsection