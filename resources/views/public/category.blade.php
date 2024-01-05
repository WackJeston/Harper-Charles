@extends('layout')

@if ($categories)
	@section('title', 'Shop')
@else
	@section('title', $category->title)
@endif

@section('content')
  <main class="category">

		@if ($errors->any())
      <div id="publicerror" class="lt floating">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
			<div id="publicmessage" class="lt floating">
				<publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@if (count($banners) > 0)
			<div id="categorybanner">
				<categorybanner
				:banners="{{ json_encode($banners) }}"
				title="{{ $bannerTitle }}"
				description="{{ $bannerDescription }}"
				/>
			</div>
		@endif

		@if (!$categories)
			<div class="clear-box large center-desktop dk">
				<h1>{{ $category->title }}</h1>
				<h3>{{ $category->subtitle }}</h3>
				<p>{{ $category->description }}</p>
			</div>
		@endif

		<div class="category-grid">
			@foreach ($items as $item)
				<a href="/{{ $url }}/{{ $item->id }}" class="item">
					<div class="image-container">
						@if (!empty($item->fileName))
							<div class="image" style="background-image: url('{{ $item->fileName }}')"></div>
						@else
							<div class="no-image">
								<i class="fa-solid fa-image"></i>
							</div>
						@endif
					</div>

					<h3>{{ $item->title }}</h3>
				</a>
			@endforeach
		</div>

  </main>
@endsection
