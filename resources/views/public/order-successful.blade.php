@extends('body-public')

@section('title', 'Order Successful')

@section('content')
  <main class="dk order-successful" id="checkout-page">

    <div id="deliveryMarker" class="checkout-title section-width">
			<h1>Order Successful</h1>
			<p>Thank you for choosing to order with us.<br>Updates about your order will be emailed to <strong>{{ auth()->user()->email }}</strong>. <small><a href="/account">change email</a></small></p>
		</div>

    @if ($errors->any())
      <div id="alerterror" class="lt"
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		<div id="checkoutsuccess" class="dk checkout-section section-width">
			<checkoutsuccess 
			:order="{{ json_encode($order) }}"
			:invoice="{{ json_encode($invoice) }}"
			/>
		</div>

  </main>
@endsection
