@extends('body-admin')

@section('title', 'Order Profile')

@section('content')
  <main class="order-profile">

		<div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/orders">Orders</a>
    </div>

    <h1 class="dk align-center">Order Profile (#{{ $order->id }}) <span class="string-container">{{ ucfirst($order->status) }}</span></h1>

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

		<div id="orderprofilefunctions">
      <orderprofilefunctions
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:order="{{ json_encode($order) }}"
				:notesform="{{ json_encode($notesForm) }}"
				:notestable="{{ json_encode($notesTable) }}"
    	/>
    </div>

		<div class="page-column-container columns-2">
			<div class="page-column">
				<ul class="web-box profile-details">
					<li>Summary</li>
					<li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
					<li><strong>Type:</strong> {{ $order->type }}</li>
					<li><strong>Total:</strong> £{{ $order->total }}</li>
					<li><strong>{{ ucfirst($order->contactType) }}:</strong> <a class="display-link" href="/admin/{{ $order->contactType }}-profile/{{ $order->userId }}">{{ $order->user }} (#{{ $order->userId }})</a></li>
					<li><strong>Order Date:</strong> {{ $order->created_at }}</li>
				</ul>

				@if (!empty($order->primaryNote))
					<div class="web-box">
						<strong>Order Note:</strong>
						<p>
							{{ $order->primaryNote }}
						</p>
					</div>
				@endif
			</div>

			<div class="page-column grid">
				<div class="web-box">
					<strong>Billing Address:</strong>
					<ul>
						<li>{{ $addresses['billing']->firstName }} {{ $addresses['billing']->lastName }}</li>
						<li>{{ $addresses['billing']->line1 }}</li>
						@if ($addresses['billing']->line2)
							<li>{{ $addresses['billing']->line2 }}</li>
						@endif
						@if ($addresses['billing']->line3)
							<li>{{ $addresses['billing']->line3 }}</li>
						@endif
						<li>{{ $addresses['billing']->city }}</li>
						<li>{{ $addresses['billing']->region }}, {{ $addresses['billing']->country }}</li>
						<li>{{ $addresses['billing']->postCode }}</li>
						
						@if ($addresses['billing']->phone)
							<li>{{ $addresses['billing']->phone }}</li>
						@endif
						@if ($addresses['billing']->email)
							<li>{{ $addresses['billing']->email }}</li>
						@endif
					</ul>
				</div>

				<div class="web-box">
					<strong>Delivery Address:</strong>
					<ul>
						<li>{{ $addresses['delivery']->firstName }} {{ $addresses['delivery']->lastName }}</li>
						<li>{{ $addresses['delivery']->line1 }}</li>
						@if ($addresses['delivery']->line2)
							<li>{{ $addresses['delivery']->line2 }}</li>
						@endif
						@if ($addresses['delivery']->line3)
							<li>{{ $addresses['delivery']->line3 }}</li>
						@endif
						<li>{{ $addresses['delivery']->city }}</li>
						<li>{{ $addresses['delivery']->region }}, {{ $addresses['delivery']->country }}</li>
						<li>{{ $addresses['delivery']->postCode }}</li>
						
						@if ($addresses['delivery']->phone)
							<li>{{ $addresses['delivery']->phone }}</li>
						@endif
						@if ($addresses['delivery']->email)
							<li>{{ $addresses['delivery']->email }}</li>
						@endif
					</ul>
				</div>
			</div>
		</div>

		@php
			echo $itemsTable['html'];
			echo $transactionsTable['html'];
		@endphp

  </main>
@endsection
