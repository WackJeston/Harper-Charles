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

    <div id="categoryprofilefunctions">
      <categoryprofilefunctions
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:category="{{ json_encode($category) }}" 
				:editform="{{ json_encode($editForm) }}"
				:imagesform="{{ json_encode($imagesForm) }}"
				:imagestable="{{ json_encode($imagesTable) }}" 
				:addproductform="{{ json_encode($addProductForm) }}"
				:createproductform="{{ json_encode($createProductForm) }}"
				:productstable="{{ json_encode($productsTable) }}" />
    </div>

		<div class="web-box profile-main">
			<div class="wb-row">
				<ul>
					<li><strong>Title: </strong>{{ $category->title }}</li>
					@if ($category->subtitle)
						<li><strong>Subtitle: </strong>{{ $category->subtitle }}</li>
					@endif
					@if ($category->description)
						<li class="text-box"><strong>Description: </strong>{{ $category->description }}</li>
					@endif
					@if ($category->created_at)
						<li><strong>Created At: </strong>{{ $category->created_at }}</li>
					@endif
				</ul>

				@if ($primaryImage != null)
					<div class="wb-image" style="background-image: url('{{ $primaryImage }}');"></div>
				@endif
			</div>
		</div>
  </main>
@endsection
