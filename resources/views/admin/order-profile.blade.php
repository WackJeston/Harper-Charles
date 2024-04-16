@extends('body-admin')

@section('title', 'Order Profile')

@section('content')
  <main class="order-profile">

		<div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/orders">Orders</a>
    </div>

    <h1 class="dk">Order Profile (#{{ $order->id }})</h1>

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

    <div class="web-box profile-main">
			<div class="wb-row">
				<ul>
					<li><strong>Status:</strong> <span class="string-container">{{ ucfirst($order->status) }}</span></li>
					<li><strong>Type:</strong> {{ $order->type }}</li>
					<li><strong>Total:</strong> £{{ $order->total }}</li>
					<li><strong>{{ ucfirst($order->contactType) }}:</strong> <a class="display-link" href="/admin/{{ $order->contactType }}-profile/{{ $order->userId }}">{{ $order->user }} (#{{ $order->userId }})</a></li>
					<li><strong>Order Date:</strong> {{ $order->created_at }}</li>
					<div class="list-row">
						<li class="text-box">
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
						</li>

						<li class="text-box">
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
						</li>
					</div>
					@if (!empty($order->primaryNote))
						<p class="text-box">
							<strong>Order Note:</strong>
							{{ $order->primaryNote }}
						</p>
					@endif
				</ul>
			</div>
		</div>

		@php
			echo $itemsTable['html'];
			echo $transactionsTable['html'];
		@endphp

  </main>
@endsection
