@extends('layout')

@section('title', 'Account')

@section('content')
  <main class="dk" id="account">

    <h1 class="section-width"><i class="fa-solid fa-user-gear"></i> Account</h1>

    @if ($errors->any())
      <div id="alerterror" class="lt section-width"
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt section-width">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    <div id="accountfunctions" class="section-width">
      <accountfunctions 
				:user="{{ $sessionUser }}"
				:orders="{{ json_encode($orders) }}"
			/>
    </div>

    <div class="web-box dk section-width">
			<ul>
				<li><strong>Name:</strong> {{ $sessionUser['firstName'] }} {{ $sessionUser['lastName'] }}</li>
				<li><strong>Email:</strong> {{ $sessionUser['email'] }}</li>
			</ul>
    </div>

  </main>
@endsection
