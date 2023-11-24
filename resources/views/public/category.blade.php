@extends('layout')

@section('title', 'Category')

@section('content')
  <main class="category">

		@if (isset($categories))
			@foreach ($categories as $category)
				{{-- <div id="categoryproducts">
					<categoryproducts
					:products="{{ json_encode($products) }}"
					:categories="{{ json_encode($categories) }}"
					:categoryimages="{{ json_encode($categoryImages) }}"
					initialcategory="{{ $initialCategory }}"
					/>
				</div> --}}
			@endforeach

		@else
			@if (count($banners) > 0)
				<div id="categorybanner">
					<categorybanner
					publicasset="{{ env('AWS_ASSET_URL') }}"
					:banners="{{ json_encode($banners) }}"
					/>
				</div>
			@endif

			<div class="clear-box large center-mobile dk">
				<h1>{{ $category->title }}</h1>
				<h3>{{ $category->subtitle }}</h3>
				<p>{{ $category->description }}</p>
			</div>

			{{-- <div id="categoryproducts">
				<categoryproducts
				:products="{{ json_encode($products) }}"
				:categories="{{ json_encode($categories) }}"
				:categoryimages="{{ json_encode($categoryImages) }}"
				initialcategory="{{ $initialCategory }}"
				/>
			</div> --}}
		@endif

  </main>
@endsection
