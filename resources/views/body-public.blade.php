@extends('layout')

@section('body-public')

	@php
		$basketCount = 0;

		if (auth()->user() != null) {
			$basketCountData = DB::select('SELECT
				o.items
				FROM orders AS o
				WHERE o.status = "basket" 
				AND o.userId = ?
				LIMIT 1',
				[auth()->user()['id']]
			);

			if (!empty($basketCountData)) {
				$basketCount = $basketCountData[0]->items;
			}
		}
	@endphp

	<div id="vuemenu">
		<vuemenu
			sitetitle="{{ env('APP_NAME') }}"
			publicasset="{{ env('ASSET_PATH') }}"
			:publiclinks="{{ json_encode($publicLinks) }}"
			:userlinks="{{ json_encode($userLinks) }}"
			:socials="{{ json_encode($socials) }}"
			:sessionuser="{{ auth()->user() }}"
			basketcount="{{ $basketCount }}"
		/>
	</div>

	<div id="page-container">
		<div id="vueheader">
			<vueheader
				sitetitle="{{ env('APP_NAME') }}"
				sitetitlemini="{{ env('APP_NAME_MINI') }}"
				publicasset="{{ env('ASSET_PATH') }}"
				:publiclinks="{{ json_encode($publicLinks) }}"
				:userlinks="{{ json_encode($userLinks) }}"
				:socials="{{ json_encode($socials) }}"
				:sessionuser="{{ auth()->user() }}"
				basketcount="{{ $basketCount }}"
			/>
		</div>

		@yield('content')

		<div class="image-viewer" style="display: none;">
			<img class="viewer-image">
			<div class="viewer-overlay"></div>
			<i class="fa-solid fa-xmark" onclick="closeImage()"></i>
		</div>

		@if (!session()->has('_previous'))
			<div id="loading-screen">
				<img async src="{{ env('ASSET_PATH') . 'website-logo.svg' }}" alt="logo" class="logo">
			</div>
		@endif

		<div id="vuefooter">
			<vuefooter
				sitetitle="{{ env('APP_NAME') }}"
				publicasset="{{ env('ASSET_PATH') }}"
				:publiclinks="{{ json_encode($publicLinks) }}"
				:userlinks="{{ json_encode($userLinks) }}"
				:socials="{{ json_encode($socials) }}"
				:sessionuser="{{ auth()->user() }}"
			/>
		</div>
	</div>
  
@endsection