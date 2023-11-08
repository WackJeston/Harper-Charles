@extends('layout')

@section('title', 'Settings')

@section('content')
  <main class="settings">

    <h2 class="dk">Settings</h2>

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
			echo $form['html'];
		@endphp

  </main>
@endsection
