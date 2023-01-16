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
      <admincontactfunctions :contact="{{ json_encode($contact) }}" />
    </div>

    <div id="admincontactmain" class="dk">
      <admincontactmain :contact="{{ json_encode($contact) }}" />
    </div>

  </main>
@endsection
