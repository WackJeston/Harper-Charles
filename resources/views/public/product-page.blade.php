@extends('layout')

@section('title', $product['title'])

@section('content')
  <main class="product-page dk">

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

    <h1>{{ $product['title'] }}</h1>

    <div id="productpagemain">
      <productpagemain
        :product="{{ json_encode($product) }}"
        :images="{{ json_encode($productImages) }}"
        count="{{ $imageCount }}"
        :variants="{{ json_encode($variants) }}"
      />
    </div>

		<h2 class="product-description-title">Product Description</h2>
    <p class="product-description">{{ $product->description }}</p>

  </main>
@endsection
