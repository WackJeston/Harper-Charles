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
				:deliveryform="{{ json_encode($deliveryForm) }}"
				:notesform="{{ json_encode($notesForm) }}"
				:notestable="{{ json_encode($notesTable) }}"
    	/>
    </div>

		<div class="page-column-container columns-2">
			<div class="page-column">
				<div id="timeline">
					@foreach ($statuses as $i => $status)
						@if ($i != 'new')
							<div class="dash"></div>
						@endif

						<div class="timeline-item">
							@if ($status == $order->status)
								@php
									$statusCheck = true;
								@endphp

								<i class="fa-solid fa-circle-dot"></i>
							@else
								@if (!$statusCheck)
									<i class="fa-solid fa-circle-check"></i>
								@else
									<i class="fa-regular fa-circle"></i>
								@endif
							@endif

							<small>{{ $status }}</small>
						</div>
					@endforeach
				</div>

				<ul class="web-box profile-details">
					<li>Summary</li>
					<li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
					<li><strong>Type:</strong> {{ $order->type }}</li>
					<li><strong>Total:</strong> £{{ $order->total }}</li>
					<li><strong>{{ ucfirst($order->contactType) }}:</strong> <a class="display-link" href="/admin/{{ $order->contactType }}-profile/{{ $order->userId }}">{{ $order->user }} (#{{ $order->userId }})</a></li>
					<li><strong>Order Date:</strong> {{ $order->created_at }}</li>
					@if (!empty($order->deliveryDate))
						<li><strong>Delivery Date: </strong>{{ date('d/m/Y H:m:s', strtotime($order->deliveryDate)) }}</li>
					@else
						<li><strong>Delivery Date: </strong><span class="text-red">Undefined</span></li>
					@endif
				</ul>
			</div>

			<div class="page-column grid">
				<div class="web-box">
					<strong>Billing Address:</strong>
					<ul>
						<li>{{ $order->billingFirstName }} {{ $order->billingLastName }}</li>
						<li>{{ $order->billingLine1 }}</li>
						@if ($order->billingLine2)
							<li>{{ $order->billingLine2 }}</li>
						@endif
						@if ($order->billingLine3)
							<li>{{ $order->billingLine3 }}</li>
						@endif
						<li>{{ $order->billingCity }}</li>
						<li>{{ $order->billingRegion }}, {{ $order->billingCountry }}</li>
						<li>{{ $order->billingPostCode }}</li>
						
						@if ($order->billingPhone)
							<li>{{ $order->billingPhone }}</li>
						@endif
						@if ($order->billingEmail)
							<li>{{ $order->billingEmail }}</li>
						@endif
					</ul>
				</div>

				<div class="web-box">
					<strong>Delivery Address:</strong>
					<ul>
						<li>{{ $order->deliveryFirstName }} {{ $order->deliveryLastName }}</li>
						<li>{{ $order->deliveryLine1 }}</li>
						@if ($order->deliveryLine2)
							<li>{{ $order->deliveryLine2 }}</li>
						@endif
						@if ($order->deliveryLine3)
							<li>{{ $order->deliveryLine3 }}</li>
						@endif
						<li>{{ $order->deliveryCity }}</li>
						<li>{{ $order->deliveryRegion }}, {{ $order->deliveryCountry }}</li>
						<li>{{ $order->deliveryPostCode }}</li>
						
						@if ($order->deliveryPhone)
							<li>{{ $order->deliveryPhone }}</li>
						@endif
						@if ($order->deliveryEmail)
							<li>{{ $order->deliveryEmail }}</li>
						@endif
					</ul>
				</div>

				@if (!empty($order->primaryNote))
					<div class="web-box">
						<strong>Order Note:</strong>
						<p>
							{{ $order->primaryNote }}
						</p>
					</div>
				@endif
			</div>
		</div>

		@php
			echo $itemsTable['html'];
			echo $transactionsTable['html'];
		@endphp

  </main>
@endsection
