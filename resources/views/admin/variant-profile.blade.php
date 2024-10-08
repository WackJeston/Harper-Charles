@extends('body-admin')

@section('title', 'Variant Profile')

@section('content')
  <main class="variant-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/variants">Variants</a>
    </div>

    <h1 class="dk">Variant Profile (<span class="text-red">{{ $variant->title }}</span> #{{ $variant->id }}) <span class="text-muted">{{$variant->reference}}</span></h1>

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

    <div id="variantprofilefunctions" class="lt">
      <variantprofilefunctions 
				pageshowmarker="{{ session()->get('pageShowMarker') }}"
				:variant="{{ json_encode($variant) }}" 
				:editform="{{ json_encode($editForm) }}"
				:subvariantsform="{{ json_encode($subVariantsForm) }}"
				:subvariantstable="{{ json_encode($subVariantsTable) }}" 
			/>
    </div>

  </main>
@endsection
