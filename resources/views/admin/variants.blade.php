@extends('layout')

@section('title', 'Product Variants')

@section('content')
  <main class="variants">

    <h1 class="dk">Product Variants</h1>

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

    <div id="variantscreate" class="dk">
      <variantscreate
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:createform="{{ json_encode($createForm) }}"
			/>
    </div>

    @php
			echo $variantsTable['html'];
		@endphp

  </main>
@endsection
