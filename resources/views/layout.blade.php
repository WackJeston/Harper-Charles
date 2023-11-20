<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
      @if (str_contains(url()->current(), '/admin'))
        <title>Admin | @yield('title')</title>
      @else
        <title>{{ env('APP_NAME') }} | @yield('title')</title>
      @endif
    @else
      <title>{{ env('APP_NAME') }}</title>
    @endif

		<link rel="preload" href="{{ env('AWS_ASSET_URL_PUBLIC') . 'website-logo.svg' }}" as="image">
		<link rel="preload" href="{{ env('AWS_ASSET_URL_PUBLIC') . 'website-logo-white.svg' }}" as="image">
		<link rel="preload" href="{{ env('AWS_ASSET_URL_PUBLIC') . 'website-title.svg' }}" as="image">

		@if (session()->has('preloaded-images'))
			@foreach (session()->get('preloaded-images') as $url)
				<link rel="preload" href="{{ $url }}" as="image">
			@endforeach
		@endif

    {{-- Fonts --}}
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
		<link href="/fontawesome/css/fontawesome.min.css" rel="stylesheet">
		<link href="/fontawesome/css/all.min.css" rel="stylesheet">

    {{-- Favicon --}}
    @if(str_contains(url()->current(), '/admin'))
			<link async rel="apple-touch-icon" sizes="180x180" href="/favicons/admin-apple-touch-icon.png">
			<link async rel="icon" type="image/png" sizes="32x32" href="/favicons/admin-favicon-32x32.png">
			<link async rel="icon" type="image/png" sizes="16x16" href="/favicons/admin-favicon-16x16.png">
			<link async rel="manifest" href="/favicons/admin-site.webmanifest">
    @else
			<link async rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
			<link async rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
			<link async rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
			<link async rel="manifest" href="/favicons/site.webmanifest">
    @endif

    {{-- stylesheet --}}
    @if(str_contains(url()->current(), '/admin'))
      <link href="{{ URL::asset('../css/admin.css') }}" rel="stylesheet" type="text/css" >
    @else
      <link href="{{ URL::asset('../css/app.css') }}" rel="stylesheet" type="text/css" >
    @endif

    {{-- Laravel Styles --}}
    <style>
      /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
    </style>
  </head>

  <body>
		@php
			$sessionUser = auth()->user();
		@endphp

		@if(str_contains(url()->current(), '/admin/'))
			{{-- ADMIN --}}
			@php
				$notificationsPre = DB::select('SELECT
					n.id,
					n.group,
					n.name,
					nu.id AS notificationUserId,
					IF(nu.email, 1, 0) AS email
					FROM notification AS n
					LEFT JOIN notification_user AS nu ON nu.notificationId=n.id AND nu.userId = ?', 
					[auth()->user()['id']]
				);

				$notifications = [];

				foreach ($notificationsPre as $i => $notification) {
					$notifications[$notification->group][] = $notification;
				}
			@endphp
			
      <div id="admin-container">
        <div id="adminheader">
          @if(str_contains(url()->current(), '/dashboard'))
            <Adminheader
              sitetitle="{{ env('APP_NAME') }}"
              :adminlinks="{{ json_encode($adminLinks) }}"
              showHome="{{ json_encode(true) }}"
              :sessionuser="{{ $sessionUser }}"
							:notifications="{{ json_encode($notifications) }}"
            />
          @else
            <Adminheader
              sitetitle="{{ env('APP_NAME') }}"
              :adminlinks="{{ json_encode($adminLinks) }}"
              showHome="{{ json_encode(false) }}"
              :sessionuser="{{ $sessionUser }}"
							:notifications="{{ json_encode($notifications) }}"
            />
          @endif
        </div>

        @yield('content')

				<div class="image-viewer" style="display: none;">
					<img class="viewer-image">
					<div class="viewer-overlay"></div>
					<i class="fa-solid fa-xmark" onclick="closeImage()"></i>
				</div>

				<div class="warning-overlay" style="display: none;" onclick="closeDeleteWarning()">
					<div class="web-box warning-box dk">
						<h3 class="warning">WARNING</h3>
						<p></p>
						<div class="row">
							<a id="delete-link"><button type="button" name="delete" class="delete">Delete</button></a>
							<button type="button" name="cancel" class="cancel" onclick="closeDeleteWarning()">Cancel</button>
						</div>
					</div>
				</div>

        <div id="adminfooter">
          <Adminfooter
            sitetitle="{{ env('APP_NAME') }}"
            :adminlinks="{{ json_encode($adminLinks) }}"
          />
        </div>
      </div>

    @elseif (str_contains(url()->current(), '/admin') || str_contains(url()->current(), '/admin-registration'))
      <div id="admin-container">
        @yield('content')
      </div>

		@elseif (str_contains(url()->current(), '/checkout'))
			<div id="page-container">
				<header id="checkout-header" class="lt">
					<nav class="desktop-nav">
						<a href="/" class="title section-width">
							<h2 id="header-title">{{ env('APP_NAME') }}</h2>
							<h2 id="header-title-mini">{{ env('APP_NAME_MINI') }}</h2>
						</a>
					</nav>
				</header>

				@yield('content')
			</div>

    @else

      <div id="vuemenu">
        <vuemenu
          sitetitle="{{ env('APP_NAME') }}"
					publicasset="{{ env('AWS_ASSET_URL_PUBLIC') }}"
          :publiclinks="{{ json_encode($publicLinks) }}"
          :userlinks="{{ json_encode($userLinks) }}"
					:socials="{{ json_encode($socials) }}"
					:sessionuser="{{ $sessionUser }}"
        />
      </div>

      <div id="page-container">
        <div id="vueheader">
          <vueheader
            sitetitle="{{ env('APP_NAME') }}"
            sitetitlemini="{{ env('APP_NAME_MINI') }}"
            publicasset="{{ env('AWS_ASSET_URL_PUBLIC') }}"
            :publiclinks="{{ json_encode($publicLinks) }}"
            :userlinks="{{ json_encode($userLinks) }}"
						:socials="{{ json_encode($socials) }}"
            :sessionuser="{{ $sessionUser }}"
          />
        </div>

        @yield('content')

				<div class="image-viewer-container">
					<div class="image-viewer" style="display: none;">
						<img class="viewer-image">
						<div class="viewer-overlay"></div>
						<i class="fa-solid fa-xmark" onclick="closeImage()"></i>
					</div>
				</div>

        <div id="vuefooter">
          <vuefooter
						sitetitle="{{ env('APP_NAME') }}"
						publicasset="{{ env('AWS_ASSET_URL_PUBLIC') }}"
						:publiclinks="{{ json_encode($publicLinks) }}"
						:userlinks="{{ json_encode($userLinks) }}"
						:socials="{{ json_encode($socials) }}"
						:sessionuser="{{ $sessionUser }}"
          />
        </div>
      </div>
    @endif

    <script src="{{ mix('js/app.js') }}"></script>
  
		{{-- Ajax --}}
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		{{-- Custom JS --}}
		<script src="/js/dataTable.js"></script>
		<script src="/js/dataForm.js"></script>
		<script src="/js/functions.js"></script>

		<script>
			// DataTable
			setTableMargin();
			hideTableColumnsLoop();

			// DataForm
			setPasswordToggles();
			setFileInputs();
		</script>
	</body>
</html>
