@extends('layout')

@section('title', 'Account')

@section('content')
  <main class="dk" id="account">

    <h1 class="section-width">
			@switch($action)
				@case('account')
					<i class="fa-solid fa-user-gear"></i>
					@break
				@case('order')
					<i class="fa-solid fa-box-archive"></i>
					@break
			@endswitch
			 {{ ucfirst($action) }}
		</h1>

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

		@switch($action)
			@case('account')
				<div id="accountfunctions" class="section section-width">
					<accountfunctions 
						:user="{{ $sessionUser }}"
						:orders="{{ json_encode($orders) }}"
					/>
				</div>
				@break
			@case('order')
				<div id="accountorder" class="section section-width">
					<accountorder 
						:user="{{ $sessionUser }}"
						:order="{{ json_encode($order) }}"
						:invoice="{{ json_encode($invoice) }}"
					/>
				</div>
				@break
		@endswitch

  </main>
@endsection
