@extends('email')

@section('title', 'New Enquiry')

@section('content')

	<ul>
		<li><strong>Name:</strong> {{ $record->name }}</li>
		<li><strong>Email:</strong> {{ $record->email }}</li>
		<li><strong>Phone:</strong> {{ $record->phone }}</li>
	</ul>

	<h2>{{ $record->subject }}</h2>
	<p>{{ $record->message }}</p>
  
@endsection