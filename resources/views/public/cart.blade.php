@extends('layout')

@section('title', 'Cart')

@section('content')
  <main class="lt" id="cart">

    <h1 class="page-margin"><i class="fa-solid fa-cart-shopping"></i> Cart</h1>

    @if ($errors->any())
      <div id="alerterror" class="lt page-margin">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt page-margin">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="cartitems" class="page-margin">
      <cartitems :items="{{ json_encode($cartItems) }}" :variants="{{ json_encode($variants) }}" />
    </div>

  </main>
@endsection
