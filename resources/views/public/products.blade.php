@extends('layout')

@section('title', 'Products')

@section('content')
  <main class="products">

    <div id="products">
      <Products
      :products="{{ json_encode($products) }}"
      :categories="{{ json_encode($categories) }}"
      :categoryimages="{{ json_encode($categoryImages) }}"
      initialcategory="{{ $initialCategory }}"
      />
    </div>

  </main>
@endsection
