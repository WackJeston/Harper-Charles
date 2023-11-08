@extends('layout')

@section('title', 'Contact')

@section('content')
  <main class="contact">

    <h2 class="dk">Contact</h2>

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

    <div id="admincontactfunctions" class="dk">
      <admincontactfunctions 
				:contact="{{ json_encode($contact) }}"
				:editform="{{ json_encode($editForm) }}"
				:emailform="{{ json_encode($emailForm) }}" 
				:emailstable="{{ json_encode($emailsTable) }}" 
				:phoneform="{{ json_encode($phoneForm) }}" 
				:phonestable="{{ json_encode($phonesTable) }}"
				:urlform="{{ json_encode($urlForm) }}" 
				:urlstable="{{ json_encode($urlsTable) }}"
			/>
    </div>

    <div class="web-box contact-main">
			<div>
				<ul>
					<li><strong>Address</strong></li>
					@if (isset($contact['line1']))
						<li>{{ $contact['line1'] }}</li>
					@endif
					@if (isset($contact['line2']))
						<li>{{ $contact['line2'] }}</li>
					@endif
					@if (isset($contact['line3']))
						<li>{{ $contact['line3'] }}</li>
					@endif
					@if (isset($contact['city']))
						<li>{{ $contact['city'] }}</li>
					@endif
					@if (isset($contact['region']))
						<li>{{ $contact['region'] }}</li>
					@endif
					@if (isset($contact['country']))
						<li>{{ $contact['country'] }}</li>
					@endif
					@if (isset($contact['postcode']))
						<li>{{ $contact['postcode'] }}</li>
					@endif
				</ul>
			</div>
	
			<div class="email-phone-section">
				@if (isset($contact['email']))
					<ul>
						<li><strong>Emails</strong></li>
						@foreach ($contact['email'] as $email)
							<li class="label"><small><strong>{{ $email['label'] }}</strong></small></li>
							<li>{{ $email['value'] }}</li>
						@endforeach
					</ul>
				@endif

				@if (isset($contact['phone']))
					<ul>
						<li><strong>Phone Numbers</strong></li>
							@foreach ($contact['phone'] as $phone)
								<li class="label"><small><strong>{{ $phone['label'] }}</strong></small></li>
								<li>{{ $phone['value'] }}</li>
							@endforeach
					</ul>
				@endif

				@if (isset($contact['url']))
					<ul>
						<li><strong>URLs</strong></li>
							@foreach ($contact['url'] as $url)
								<li class="label"><small><strong>{{ $url['label'] }}</strong></small></li>
								<li>{{ $url['value'] }}</li>
							@endforeach
					</ul>
				@endif
			</div>
		</div>

  </main>
@endsection
