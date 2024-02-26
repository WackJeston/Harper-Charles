@extends('body-admin')

@section('title', 'Customer Profile')

@section('content')
  <main class="customer-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/customers">Customers</a>
    </div>

    <h1 class="dk">Customer Profile (#{{ $customer->id }})</h1>

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
      <customerprofilefunctions 
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:customer="{{ json_encode($customer) }}" 
				:editform="{{ json_encode($editForm) }}"
				:orderstable="{{ json_encode($ordersTable) }}"
			/>
    </div>

    <div class="web-box profile-main">
			<div class="wb-row">
				<ul>
					<li><strong>Name: </strong>{{ $customer->firstName }} {{ $customer->lastName }}</li>
					<li><strong>Email: </strong>{{ $customer->email }}</li>
				</ul>
			</div>
		</div>

  </main>
@endsection
