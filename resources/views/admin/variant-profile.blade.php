@extends('layout')

@section('title', 'Variant Profile')

@section('content')
  <main class="variant-profile">

    <div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/variants">Variants</a>
    </div>

    <h1 class="dk">Variant Profile (<span>{{ $variant->title }}</span> #{{ $variant->id }})</h1>

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
      <variantprofilefunctions :variant="{{ json_encode($variant) }}" :subvariants="{{ json_encode($subVariants) }}" />
    </div>

  </main>
@endsection
