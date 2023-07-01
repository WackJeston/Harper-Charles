@extends('layout')

@section('title', 'Test')
@section('js', 'dataTable')

@section('content')
  <main class="test">

    <h1 class="dk">Test Page</h1>

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

		{{ $dataTable->display() }}

		{{ $table2->display() }}

  </main>
@endsection
