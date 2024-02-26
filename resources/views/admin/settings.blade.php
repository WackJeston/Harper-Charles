@extends('body-admin')

@section('title', 'Settings')

@section('content')
  <main class="settings">
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

		<div class="page-column-container columns-2">
			<div class="page-column">
				<h2>Settings</h2>

				@php
					echo $form['html'];
				@endphp
			</div>
			<div class="page-column">
				<h2>Cache</h2>

				<div class="page-button-row web-box">
					<a href="/settingsClearCache/public-page-home" class="page-button">Home</a>
					<a href="/settingsClearCache/public-page-shop" class="page-button">Shop</a>
				</div>
			</div>
		</div>

		<h2>Cron Jobs</h2>
		
		@php
			echo $cronJobs['html'];
		@endphp
  </main>
@endsection
