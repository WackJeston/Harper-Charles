@extends('body-public')

@section('title', 'Account')

@section('content')
  <main class="dk" id="account">

		@if ($action == 'order')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/account">Account</a>
			</div>
		@endif

    <h1 class="section-width">
			@switch($action)
				@case('account')
					<i class="fa-solid fa-user-gear"></i>
					{{ ucfirst($action) }}
					@break
				@case('order')
					<i class="fa-solid fa-box-archive"></i>
					{{ ucfirst($action) }}
					@break
			@endswitch
		</h1>

    @if ($errors->any())
      <div id="publicerror" class="lt section-width">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
			<div id="publicmessage" class="lt section-width">
				<publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@switch($action)
			@case('account')
				<div id="accountfunctions" class="section section-width">
					<accountfunctions
						pageshowmarker="{{ session()->get('pageShowMarker') }}"
						:user="{{ auth()->user() }}"
						:orders="{{ json_encode($orders) }}"
						:orderstable="{{ json_encode($ordersTable) }}"
					/>
				</div>
				@break
			@case('order')
				<div id="accountorder" class="section section-width">
					<accountorder 
						:user="{{ auth()->user() }}"
						:order="{{ json_encode($order) }}"
						:invoice="{{ json_encode($invoice) }}"
						:notesform="{{ json_encode($notesForm) }}"
						:notestable="{{ json_encode($notesTable) }}"
						:itemstable="{{ json_encode($itemsTable) }}"
					/>
				</div>
				@break
		@endswitch

  </main>
@endsection
