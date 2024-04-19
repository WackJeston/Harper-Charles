@extends('email')

@section('title', 'Order Successful')

@section('content')

	<h1>Order Successful</h1>
	<p>Testing order emails for the first time!</p>

	{{-- <ul>
		<li><strong>Name:</strong> {{ $record->name }}</li>
		<li><strong>Email:</strong> {{ $record->email }}</li>
		<li><strong>Phone:</strong> {{ $record->phone }}</li>
	</ul>

	<h2>{{ $record->subject }}</h2>
	<p>{{ $record->message }}</p> --}}
  
@endsection