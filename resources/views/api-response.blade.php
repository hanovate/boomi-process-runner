@extends('layouts.base')

@section('main-title','API Response')

@section('breadcrumb')
@endsection

@section('content')
<div id="primary">

  <div class="row">

    <div class="col-md-12">
@if ($statusCode == 200)
      <h1>The BOOMI Execution Process API {{ config('boomi.processName') }} has responded with an OK.</h1>
@else
      <h1>The BOOMI Execution Process API {{ config('boomi.processName') }} has responded with {{ $statusCode }}.</h1>
@endif

<h3>Content of response (This could be empty):</h3>
<p>{{ $bodyResponse }}</p>
    </div>

  </div>

</div>
@endsection

