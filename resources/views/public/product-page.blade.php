@extends('body-public')

@section('title', $product['title'])

@section('content')
  <main class="product-page dk">

    @if ($errors->any())
      <div id="alerterror" class="lt floating">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt floating">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <h1><strong>{{ $product['title'] }}</strong></h1>

    <section id="productpagemain" class="product-page-main web-box">
      <productpagemain
        :product="{{ json_encode($product) }}"
        :images="{{ json_encode($productImages) }}"
        count="{{ $imageCount }}"
        :variants="{{ json_encode($variants) }}"
				:specs="{{ json_encode($specs) }}"
      />
    </section>

		<section id="productpageinfo" class="product-page-tab-section">
      <productpageinfo
        :product="{{ json_encode($product) }}"
        :specs="{{ json_encode($specs) }}"
      />
    </section>
  </main>
@endsection
