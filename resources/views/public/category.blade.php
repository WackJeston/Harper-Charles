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

			<div class="clear-box large center-desktop dk">
				<h1>{{ $category->title }}</h1>
				<h3>{{ $category->subtitle }}</h3>
				<p>{{ $category->description }}</p>
			</div>

			<div id="categoryProducts">
				@foreach ($products as $product)
					<a href="/product-page/{{ $product->id }}" class="product">
						<div class="image-container">
							@if (!empty($product->fileName))
								<div class="image" style="background-image: url('{{ env('AWS_ASSET_URL') . $product->fileName }}')"></div>
							@else
								<div class="no-image">
									<i class="fa-solid fa-image"></i>
								</div>
							@endif
						</div>

						<h3>{{ $product->title }}</h3>
					</a>
				@endforeach
			</div>
		@endif

  </main>
@endsection
