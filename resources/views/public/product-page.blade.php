@extends('layout')

@section('title', $product['title'])

@section('content')
  <main class="product-page dk">

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

    <section id="productpagemain">
      <productpagemain
        :product="{{ json_encode($product) }}"
        :images="{{ json_encode($productImages) }}"
        count="{{ $imageCount }}"
        :variants="{{ json_encode($variants) }}"
				:specs="{{ json_encode($specs) }}"
      />
    </section>

		<section class="page-column-container">
			<div class="page-column product-description">
				<h2>Description</h2>
    		<p>{{ $product->description }}</p>
			</div>
			<div class="page-column product-specs">
				<h2>Secifications</h2>
				<ul>
					@foreach ($specs as $spec)
						<li><span>{{ $spec->label }}: {{ $spec->value }}</span></li>
					@endforeach
				</ul>
			</div>
		</section>

  </main>
@endsection
