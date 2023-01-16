@extends('layout')

@section('title', 'Contact')

@section('content')
  <main class="lt contact">

    <div id="contactmain">
      <contactmain :contact="{{ json_encode($contact) }}" />
    </div>

  </main>
@endsection
