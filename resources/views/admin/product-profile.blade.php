@extends('layout')

@section('title', 'Product Profile')

@section('content')
  <main class="product-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/products">Products</a>
    </div>

    <h2 class="dk">Product Profile (#{{ $product->id }})</h2>

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

    <div id="productprofilefunctions" class="dk">
      <productprofilefunctions
      :product="{{ json_encode($product) }}"
      :images="{{ json_encode($images) }}"
      :imagecount="{{ json_encode($imageCount) }}"
      :categories="{{ json_encode($categories) }}"
      :allcategories="{{ json_encode($allCategories) }}"
      :variants="{{ json_encode($variants) }}"
      :allvariants="{{ json_encode($allVariants) }}"
    />
    </div>

    <div class="web-box profile-main">
			<div class="wb-row">
				<ul>
					<li><strong>Title: </strong>{{ $product->title }}</li>
					<li><strong>Subtitle: </strong>{{ $product->subtitle }}</li>
					<li class="text-box"><strong>Description: </strong>{{ $product->description }}</li>
					<li><strong>Product Number: </strong>{{ $product->productNumber }}</li>
					<li><strong>Price: </strong>£{{ $product->price }}</li>
					@if ($product->created_at)
						<li><strong>Created At: </strong>{{ $product->created_at }}</li>
					@endif
				</ul>
				@if ($primaryImage)
					<div class="wb-image" style="background-image: url('https://hc-main.s3.eu-west-2.amazonaws.com/assets/{{ $primaryImage }}');"></div>
				@endif
			</div>
		</div>

  </main>
@endsection
