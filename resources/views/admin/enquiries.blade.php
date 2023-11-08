@extends('layout')

@if ($type == 'standard')
	@section('title', 'Enquiries')
@elseif ($type == 'feedback')
	@section('title', 'Feedback')
@elseif ($type == 'sponsors')
	@section('title', 'Sponsor Enquiries')
@endif

@section('content')
  <main class="enquiries">

		@if ($type == 'standard')
			<h2 class="dk">Enquiries</h2>
		@elseif ($type == 'feedback')
			<h2 class="dk">Feedback</h2>
		@elseif ($type == 'sponsors')
			<h2 class="dk">Sponsor Enquiries</h2>
		@endif

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

		@php
			echo $enquiriesTable['html'];
		@endphp

  </main>
@endsection
