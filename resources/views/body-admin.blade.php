@extends('layout')

@section('body-admin')

	@php
		$settingsPre = DB::select('SELECT
			n.id,
			n.group,
			n.name,
			nu.id AS notificationUserId,
			IF(nu.standard, 1, 0) AS `standard`,
			IF(nu.email, 1, 0) AS email
			FROM notification AS n
			LEFT JOIN notification_user AS nu ON nu.notificationId=n.id AND nu.userId = ?
			GROUP BY n.id', 
			[auth()->user()['id']]
		);

		$settings = [];

		foreach ($settingsPre as $i => $settingPre) {
			$settings[$settingPre->group][] = $settingPre;
		}
	@endphp

	<div id="admin-container">
		<div id="adminheader">
			@if(str_contains(url()->current(), '/dashboard'))
				<Adminheader
					sitetitle="{{ env('APP_NAME') }}"
					:adminlinks="{{ json_encode($adminLinks) }}"
					showHome="{{ json_encode(true) }}"
					:sessionuser="{{ auth()->user() }}"
					:settings="{{ json_encode($settings) }}"
				/>
			@else
				<Adminheader
					sitetitle="{{ env('APP_NAME') }}"
					:adminlinks="{{ json_encode($adminLinks) }}"
					showHome="{{ json_encode(false) }}"
					:sessionuser="{{ auth()->user() }}"
					:settings="{{ json_encode($settings) }}"
				/>
			@endif
		</div>

		@yield('content')

		<div id="adminfooter">
			<Adminfooter
				sitetitle="{{ env('APP_NAME') }}"
				:adminlinks="{{ json_encode($adminLinks) }}"
			/>
		</div>
	</div>
  
@endsection