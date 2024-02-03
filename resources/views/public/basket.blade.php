@extends('layout')

@section('title', 'Basket')

@section('content')
  <main class="dk" id="basket">

		@if (isset($basket['lines']) && count($basket['lines']) > 0)
			<h1><i class="fa-solid fa-basket-shopping"></i><br>Shopping Basket</h1>
		@else
			<h1 class="empty-basket"><i class="fa-solid fa-basket-shopping"></i><br>Shopping Basket<br>Is Empty</h1>
		@endif

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

    <div id="basketlines">
      <basketlines :basket="{{ json_encode($basket) }}" />
    </div>

  </main>
@endsection
