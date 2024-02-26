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
  
@endsection