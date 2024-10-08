@extends('body-admin')

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
				:categoryform="{{ json_encode($categoryForm) }}"
				:categoriestable="{{ json_encode($categoriesTable) }}"
				:imagesform="{{ json_encode($imagesForm) }}"
				:imagestable="{{ json_encode($imagesTable) }}"
				:specsform="{{ json_encode($specsForm) }}"
				:specstable="{{ json_encode($specsTable) }}"
				:stockform="{{ json_encode($stockForm) }}"
				:stocktable="{{ json_encode($stockTable) }}"
				:variantsform="{{ json_encode($variantsForm) }}"
				:variantstable="{{ json_encode($variantsTable) }}"
    	/>
    </div>

		<div class="page-column-container columns-2">
			<div class="page-column">
				<ul class="web-box profile-details">
					<li>Summary</li>
					<li><strong>Title: </strong>{{ $product->title }}</li>
					<li><strong>Subtitle: </strong>{{ $product->subtitle }}</li>
					@if (!is_null($product->productNumber))
						<li><strong>Product Number: </strong>{{ $product->productNumber }}</li>
					@endif
					@if (!is_null($product->orbitalVisionId))
						<li><strong>Orbital Vision: </strong>{{ $product->orbitalVisionId }}</li>
					@endif
					<li><strong>Price: </strong>£{{ $product->price }}</li>
					@if (!is_null($product->maxQuantity))
						<li><strong>Max Purchase Quantity: </strong>{{ $product->maxQuantity }}</li>
					@endif
					@if (!is_null($product->stock))
					<li><strong>Stock: </strong>{{ $product->stock }}</li>
					@endif
					@if (!is_null($product->startDate))
						<li><strong>Start Date: </strong>{{ date('d/m/Y H:m:s', strtotime($product->startDate)) }}</li>
					@endif
					@if (!is_null($product->endDate))
						<li><strong>End Date: </strong>{{ date('d/m/Y H:m:s', strtotime($product->endDate)) }}</li>
					@endif
					@if (!empty($product->updated_at))
						<li><strong>Updated At: </strong>{{ date('d/m/Y H:m:s', strtotime($product->updated_at)) }}</li>
					@endif
				</ul>

				<div class="web-box">
					<strong>Description:</strong>
					<p>{{ $product->description }}</p>
				</div>
			</div>

			<div class="page-column">
				@if ($primaryImage != null)
					<div class="web-box profile-image-container">
						<div class="profile-image" style="background-image: url('{{ $primaryImage }}');"></div>
					</div>
				@endif
			</div>
		</div>

  </main>
@endsection
