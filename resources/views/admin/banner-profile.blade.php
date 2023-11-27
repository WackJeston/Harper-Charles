@extends('layout')

@section('title', 'Banner Profile')

@section('content')
  <main class="banner-profile">

		<div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/banners">Banners</a>
    </div>

    <h1 class="dk">Banner ({{ $banner->page }} | {{ $banner->position }})</h1>

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

		<div id="bannerprofilefunctions" class="dk">
      <bannerprofilefunctions
      :banner="{{ json_encode($banner) }}"
			:slideform="{{ json_encode($slideForm) }}"
    />
    </div>

		@php
			echo $slidesTable['html'];
		@endphp

  </main>
@endsection
