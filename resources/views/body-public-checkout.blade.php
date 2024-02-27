@extends('layout')

@section('body-public-checkout')

	<div id="page-container">
		<div id="checkoutheader">
			<checkoutheader
				publicasset="{{ env('ASSET_PATH') }}"
			/>
		</div>

		@yield('content')
	</div>
  
@endsection