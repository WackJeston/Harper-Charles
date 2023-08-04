@extends('layout')

@section('title', 'Orders')

@section('content')
  <main class="orders">

    <h1 class="dk">Orders</h1>

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

    {{-- <div id="variantscreate" class="dk">
      <variantscreate :createform="{{ json_encode($createForm) }}" />
    </div> --}}

    @php
			echo $ordersTable['html'];
		@endphp

  </main>
@endsection
