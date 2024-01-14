@extends('layout')

@section('title', 'Users')

@section('content')
  <main class="users">

    <h1 class="dk">Users</h1>

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

    <div id="userscreate" class="dk">
      <userscreate
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:createform="{{ json_encode($createForm) }}"
			/>
    </div>

		@php
			echo $usersTable['html'];
		@endphp

  </main>
@endsection
