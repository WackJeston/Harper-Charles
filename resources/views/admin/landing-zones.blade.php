@extends('layout')

@section('title', 'Landing Zones')

@section('content')
  <main class="landing-zones">

    <h1 class="dk">Landing Zones</h1>

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

    <div id="lzhomecarousel" class="dk">
      <lzhomecarousel :homepagecarousel="{{ $homepageCarousel }}" homepagecarouselcount="{{ $homepageCarouselCount }}" homepagecarouselshow="{{ $homepageCarouselShow }}" />
    </div>

  </main>
@endsection
