@extends('body-admin')

@if ($type == 'standard')
	@section('title', 'Enquiries')
@elseif ($type == 'feedback')
	@section('title', 'Feedback')
@elseif ($type == 'sponsors')
	@section('title', 'Sponsor Enquiries')
@endif

@section('content')
  <main class="enquiries">

		<h1 class="dk">Enquiries Search</h1>

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
			echo $searchForm['html'];
		@endphp

		@php
			echo $enquiriesTable['html'];
		@endphp

  </main>
@endsection
