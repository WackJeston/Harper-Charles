@extends('body-admin')

@section('title', 'Banners')

@section('content')
  <main class="banners">

    <h1 class="dk">Banners</h1>

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

    {{-- <div id="variantscreate" class="dk">
      <variantscreate :createform="{{ json_encode($createForm) }}" />
    </div> --}}

    @php
			echo $bannersTable['html'];
		@endphp

  </main>
@endsection
