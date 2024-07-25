@extends('email')

@section('title', 'New Enquiry')

@section('content')

	<ul>
		<li><strong>Name:</strong> {{ $record->name }}</li>
		<li><strong>Email:</strong> {{ $record->email }}</li>
		<li><strong>Phone:</strong> {{ $record->phone }}</li>
		<li><strong>Enquiry Profile:</strong> <a href="{{ env('APP_URL') . '/admin/enquiry-profile/' . $record->id }}" target="_blank">{{ env('APP_URL') . '/admin/enquiry-profile/' . $record->id }}</a></li>
	</ul>

	<h2>{{ $record->subject }}</h2>
	<p>{{ $record->message }}</p>
  
@endsection