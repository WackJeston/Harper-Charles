@extends('layout')

@section('title', 'Cart')

@section('content')
  <main class="dk" id="cart">

    <h1><i class="fa-solid fa-cart-shopping"></i> Cart</h1>

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

    <div id="cartlines">
      <cartlines :cart="{{ json_encode($cart) }}" />
    </div>

  </main>
@endsection
