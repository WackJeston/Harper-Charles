@extends('layout')

@section('title', 'Category Profile')

@section('content')
  <main class="category-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/categories">Categories</a>
    </div>

    <h1 class="dk">Category Profile (#{{ $category->id }})</h1>

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

    <div id="categoryprofilefunctions" class="lt">
      <categoryprofilefunctions :category="{{ json_encode($category) }}" :images="{{ json_encode($images) }}" :imagecount="{{ json_encode($imageCount) }}" :products="{{ json_encode($products) }}" :allproducts="{{ $allProducts }}" />
    </div>

    <div id="categoryprofilemain" class="dk">
      <categoryprofilemain :category="{{ json_encode($category) }}" image="{{ $primaryImage }}" />
    </div>

  </main>
@endsection
