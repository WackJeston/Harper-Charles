@extends('layout')

@section('title', 'Product Profile')

@section('content')
  <main class="product-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/products">Products</a>
    </div>

    <h1 class="dk">Product Profile (#{{ $product->id }})</h1>

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
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:product="{{ json_encode($product) }}"
				:editform="{{ json_encode($editForm) }}"
				:imagesform="{{ json_encode($imagesForm) }}"
				:imagestable="{{ json_encode($imagesTable) }}"
				:specsform="{{ json_encode($specsForm) }}"
				:specstable="{{ json_encode($specsTable) }}"
				:categoryform="{{ json_encode($categoryForm) }}"
				:categoriestable="{{ json_encode($categoriesTable) }}"
				:variantsform="{{ json_encode($variantsForm) }}"
				:variantstable="{{ json_encode($variantsTable) }}"
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
						<li><strong>Created On: </strong>{{ $product->created_at }}</li>
					@endif
				</ul>
				
				@if ($primaryImage != null)
					<div class="wb-image" style="background-image: url('{{ $primaryImage }}');"></div>
				@endif
			</div>
		</div>

  </main>
@endsection
