@extends('layout')

@section('title', 'Users')

@section('content')
  <main class="users">

    <h2 class="dk">Users</h2>

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
      <userscreate :createform="{{ json_encode($createForm) }}" />
    </div>

		@php
			echo $usersTable['html'];
		@endphp

  </main>
@endsection
