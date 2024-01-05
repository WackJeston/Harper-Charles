@extends('layout')

@section('content')
  <main class="lt home-page">

		@if ($errors->any())
      <div id="publicerror" class="lt floating">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
			<div id="publicmessage" class="lt floating">
				<publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

    @if (count($landingZoneBanners) > 0)
			<div async id="bannerhometop">
				<bannerhometop
					:banners="{{ json_encode($landingZoneBanners) }}"
				/>
			</div>
		@endif

    <div class="clear-box large center-desktop dk">
      <h2>Content Section</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>

    <div id="homecategories">
      <homecategories
				:categories="{{ json_encode($categories) }}" 
			/>
    </div>

  </main>
@endsection
