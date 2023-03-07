@extends('layout')

@section('title', 'Product-page')

@section('content')
  <main class="product-page page-margin dk">

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

    <h1>{{ $product['title'] }}</h1>

    <div id="productpagemain">
      <productpagemain
        :product="{{ json_encode($product) }}"
        :images="{{ $productImages }}"
        count="{{ $imageCount }}"
        :selectedimage="{{ $primaryProductImage }}"
        :variants="{{ json_encode($variants) }}"
      />
    </div>

    <p class="product-description">{{ $product->description }}</p>

  </main>
@endsection
