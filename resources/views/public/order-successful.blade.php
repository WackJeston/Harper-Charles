@extends('layout')

@section('title', 'Order Successful')

@section('content')
  <main class="dk" id="account">

    <h1>Order Successful</h1>

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

		<div class="web-box">
			<p>Testing</p>
		</div>

  </main>
@endsection
