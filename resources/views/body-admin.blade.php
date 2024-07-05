@extends('layout')

@section('body-admin')

	@php
		$settingsPre = DB::select('SELECT
			n.id,
			n.group,
			n.name,
			COALESCE(nu.id, 0) AS notificationUserId,
			IF(nu.standard, 1, 0) AS `standard`,
			IF(nu.email, 1, 0) AS email
			FROM notification AS n
			LEFT JOIN notification_user AS nu ON nu.notificationId=n.id AND nu.userId = ?
			GROUP BY n.id', 
			[auth()->user()->id]
		);

		$settings = [];

		foreach ($settingsPre as $i => $settingPre) {
			$settings[$settingPre->group][] = $settingPre;
		}

		$notifications = DB::select('SELECT
			ne.*,
			n.group,
			n.name,
			IF(ISNULL(ne.pageId), n.url, CONCAT(n.url, "/", ne.pageId)) AS link
			FROM notification_event AS ne
			INNER JOIN notification AS n ON n.id = ne.notificationId
			WHERE ne.userId = ?
			ORDER BY ne.created_at DESC',
			[auth()->user()->id]
		);

		// dd($notifications);
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
					:notifications="{{ json_encode($notifications) }}"
				/>
			@else
				<Adminheader
					sitetitle="{{ env('APP_NAME') }}"
					:adminlinks="{{ json_encode($adminLinks) }}"
					showHome="{{ json_encode(false) }}"
					:sessionuser="{{ auth()->user() }}"
					:settings="{{ json_encode($settings) }}"
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