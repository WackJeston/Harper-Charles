@extends('layout')

@section('body-admin')

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
					:sessionuser="{{ auth()->user() }}"
					:notifications="{{ json_encode($notifications) }}"
				/>
			@else
				<Adminheader
					sitetitle="{{ env('APP_NAME') }}"
					:adminlinks="{{ json_encode($adminLinks) }}"
					showHome="{{ json_encode(false) }}"
					:sessionuser="{{ auth()->user() }}"
					:notifications="{{ json_encode($notifications) }}"
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