@extends('layouts.base')

@section('main-title','Home')

@section('content')
<div id="primary">
	<div class="row">
    <div class="col-md-12">
    @if (cas()->isAuthenticated())
		{{--
      @can('view-district')
      <p>{!! __('text.home.postlogin-header-msg') !!}</p>
	  <div class="spacer10"></div>
	  <a href="{{ route('newmodel.index') }}" class="btn btn-default" role="button">Newmodel Listing</a>
      @else
      <p>{!! __('text.home.postlogin-not-authorized') !!}</p>
      @endcan
	  --}}
    @else
      <p>Please <a href="{{ route('main.login') }}">log in</a> to access.</p>
      <p style="color:red; font-weight: bold;">{!! __('text.home.prelogin-note') !!}</p>
    @endif
    </div>
  </div>
    <hr>
@if (!cas()->isAuthenticated())
	@lang('text.home.prelogin-compatibility-msg')
@elseif (in_array(strtoupper(session('cas_user')),$allowedUsers))
	{{-- @lang('text.home.postlogin-contact-msg') --}}

	<a class="btn btn-default" href="{{route('boomi.execute')}}" role="button">Execute WAP ODSP-to-BOOMI process</a>
@else
	<p>Welcome!  There's absolutely nothing to do here for you!</p>
@endif
</div>
@endsection
