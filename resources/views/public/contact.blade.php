@extends('body-public')

@section('title', 'Contact')

@section('content')
  <main class="dk contact">

		<div class="title-section">
			<h1>Contact Us</h1>
		</div>

		@if ($errors->any())
      <div id="alerterror" class="lt page-margin">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt floating">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		<div class="content">
			<ul>
				<li><h3>Address</h3></li>
				
				@if (isset($address['line1']))
					<li>{{ $address['line1'] }}</li>
				@endif
				@if (isset($address['line2']))
					<li>{{ $address['line2'] }}</li>
				@endif
				@if (isset($address['line3']))
					<li>{{ $address['line3'] }}</li>
				@endif
				@if (isset($address['city']))
					<li>{{ $address['city'] }}</li>
				@endif
				@if (isset($address['region']))
					<li>{{ $address['region'] }}</li>
				@endif
				@if (isset($address['country']))
					<li>{{ $address['country'] }}</li>
				@endif
				@if (isset($address['postcode']))
					<li>{{ $address['postcode'] }}</li>
				@endif
			</ul>

			@if (count($contact['email']) > 0 || count($contact['phone']) > 0 || count($contact['url']) > 0)
				<ul>
					<li><h3>Contact</h3></li>

					@foreach ($contact['email'] as $email)
						<li>{{ $email->value }}</li>
					@endforeach
					@foreach ($contact['phone'] as $phone)
						<li>{{ $phone->value }}</li>
					@endforeach
					@foreach ($contact['url'] as $url)
						<li><small><strong>{{ $url->value }}</strong></small></li>
						<li><a href="{{ $url->value }}" target="_blank">{{ $url->value }}</a></li>
					@endforeach
				</ul>
			@endif
		</div>

		<div id="googlemaps">
			<googlemaps 
				:coordinates="{{ json_encode($coordinates) }}"
			/>
		</div>

    <div id="contactmain">
      <contactmain 
				:address="{{ json_encode($address) }}"
				:contact="{{ json_encode($contact) }}"
			/>
    </div>

  </main>
@endsection
