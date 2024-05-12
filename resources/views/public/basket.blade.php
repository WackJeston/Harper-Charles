@extends('body-public')

@section('title', 'Basket')

@section('content')
	@if (isset($basket->lines) && count($basket->lines) > 0)

		<main class="dk" id="basket">
			<h1><i class="fa-solid fa-basket-shopping"></i>Your Basket</h1>

			@if ($errors->any())
				<div id="publicerror" class="lt limited">
					<publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
				</div>
			@endif

			@if (session()->has('message'))
				<div id="publicmessage" class="lt limited">
					<publicmessage successmessage="{{ session()->get('message') }}" />
				</div>
			@endif

			<div id="basketlines">
				<basketlines :basket="{{ json_encode($basket) }}" />
			</div>
		</main>

	@else

		<main class="dk empty-basket" id="basket">
			<div class="empty-container">
				<div class="row">
					<i class="fa-solid fa-basket-shopping"></i>
					<h1>Your shopping basket<br>is empty.</h1>
				</div>
				<a href="/shop" class="page-button padding-large" id="continueShopping">Continue Shopping</a>
	
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
			</div>
		</main>

	@endif
@endsection
