@extends('layout')

@section('title', 'Customer Profile')

@section('content')
  <main class="customer-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/customers">Customers</a>
    </div>

    <h2 class="dk">Customer Profile (#{{ $customer->id }})</h2>

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

    <div id="customerprofilefunctions" class="dk">
      <customerprofilefunctions :customer="{{ json_encode($customer) }}" />
    </div>

    <div id="customerprofilemain" class="dk">
      <customerprofilemain :customer="{{ json_encode($customer) }}" />
    </div>

  </main>
@endsection
