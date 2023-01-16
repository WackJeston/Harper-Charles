@extends('layout')

@section('title', 'Customers')

@section('content')
  <main class="customers">

    <h2 class="dk">Customers</h2>

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

    <div id="customerscreate" class="dk">
      <customerscreate />
    </div>

    <div id="customerstable" class="dk">
      <customerstable :customers="{{ json_encode($customers) }}" />
    </div>

  </main>
@endsection
