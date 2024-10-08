@extends('body-admin')

@section('title', 'Customers')

@section('content')
  <main class="customers">

    <h1 class="dk">Customers</h1>

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

    <div id="customerscreate" class="dk">
      <customerscreate
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:createform="{{ json_encode($createForm) }}"
			/>
    </div>

		@php
			echo $customersTable['html'];
		@endphp

  </main>
@endsection
