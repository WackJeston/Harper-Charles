@extends('body-admin')

@if ($type == 'standard')
	@section('title', 'Enquiry')
@elseif ($type == 'feedback')
	@section('title', 'Feeback Enquiry')
@elseif ($type == 'sponsor')
	@section('title', 'New Sponsor Enquiry')
@endif

@section('content')
  <main class="enquiry-profile">

    @if ($type == 'standard')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/enquiries">Enquiries</a>
			</div>

			<h1 class="dk">Enquiry</h1>
		@elseif ($type == 'feedback')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/feedback">Feedback</a>
			</div>

			<h1 class="dk">Feeback Enquiry</h1>
		@elseif ($type == 'sponsor')
			<div class="link-trail">
				<i class="fa-solid fa-arrow-left"></i>
				<a href="/admin/new-sponsors">Sponsor Enquiries</a>
			</div>

			<h1 class="dk">New Sponsor Enquiry</h1>
		@endif

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
				<ul class="web-box profile-details">
					<li>Summary</li>
					<li><strong>Name:</strong> {{ $enquiry->name }}</li>
					<li><strong>Email:</strong> {{ $enquiry->email }}</li>
					<li><strong>Phone:</strong> {{ $enquiry->phone }}</li>
					<li><strong>Date:</strong> {{ date('d-m-Y', strtotime($enquiry->created_at)) }}</li>
					@if ($type != 'sponsor')
						<li><strong>Subject:</strong> {{ $enquiry->subject }}</li>
					@endif			
				</ul>
			</div>

			<div class="page-column">
				<div class="web-box">
					<strong>Message:</strong>
					<p>
						{{ $enquiry->message }}
					</p>
				</div>
			</div>
		</div>
  </main>
@endsection
