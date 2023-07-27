@extends('layout')

@section('title', 'Products')

@section('content')
  <main class="products">

    <h2 class="dk">Products</h2>

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

    <div id="productscreate" class="dk">
      <productscreate :createform="{{ json_encode($createForm) }}" />
    </div>

		@php
			echo $productsTable['html'];
		@endphp

  </main>
@endsection
