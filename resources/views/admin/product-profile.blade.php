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
      :allcategories="{{ $allCategories }}"
      :variants="{{ json_encode($variants) }}"
      :allvariants="{{ json_encode($allVariants) }}"
    />
    </div>

    <div id="productprofilemain" class="dk">
      <productprofilemain :product="{{ json_encode($product) }}" image="{{ $primaryImage }}" />
    </div>

  </main>
@endsection
