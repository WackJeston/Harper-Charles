@extends('layout')

@section('title', 'Order Successful')

@section('content')
  <main class="dk order-successful" id="checkout-page">

    <div id="deliveryMarker" class="checkout-title section-width">
			<h1>Order Successful</h1>
			<p>Thank you for choosing to order with us. We will keep you updated about your order by email.</p>
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

		<div id="checkoutsuccess" class="dk checkout-section">
			<checkoutsuccess 
			:order="{{ json_encode($order) }}"
			:products="{{ json_encode($products) }}"
			:address="{{ json_encode($address) }}"
			:invoice="{{ json_encode($invoice) }}"
			/>
		</div>

  </main>
@endsection
