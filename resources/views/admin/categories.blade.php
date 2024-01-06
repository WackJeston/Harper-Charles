@extends('layout')

@section('title', 'Product Categories')

@section('content')
  <main class="categories">

    <h1 class="dk">Product Categories</h1>

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

    <div id="categorycreate" class="dk">
      <categorycreate
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:createform="{{ json_encode($createForm) }}"
			/>
    </div>

		@php
			echo $categoriesTable['html'];
		@endphp

  </main>
@endsection
