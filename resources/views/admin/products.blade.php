@extends('body-admin')

@section('title', 'Products')

@section('content')
  <main class="products">

    <h1 class="dk">Products</h1>

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

		<div id="productscreate" class="dk">
			<productscreate
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:createform="{{ json_encode($createForm) }}"
			/>
		</div>

		@php
			echo $searchForm['html'];
		@endphp

		@php
			echo $productsTable['html'];
		@endphp

  </main>
@endsection
