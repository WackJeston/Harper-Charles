@extends('body-admin')

@section('title', 'User Profile')

@section('content')
  <main class="user-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/users">Users</a>
    </div>

    <h1 class="dk">User Profile (#{{ $user->id }})</h1>

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

    <div id="userprofilefunctions" class="dk">
      <userprofilefunctions
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:user="{{ json_encode($user) }}" 
				:editform="{{ json_encode($editForm) }}"
				:orderstable="{{ json_encode($ordersTable) }}"
			/>
    </div>

		<div class="page-column-container columns-2">
			<div class="page-column">
				<ul class="web-box profile-details">
					<li>Summary</li>
					<li><strong>Name: </strong>{{ $user->firstName }} {{ $user->lastName }}</li>
					<li><strong>Email: </strong>{{ $user->email }}</li>
					@if (!is_null($user->klaviyoId))
						<li><strong>Klaviyo ID: </strong>{{ $user->klaviyoId }}</li>
					@endif
				</ul>
			</div>
		</div>
  </main>
@endsection
