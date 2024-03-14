@extends('layouts.afterlogin')
@section('content')
    <div class="container-fluid">
        
        <iframe src="{{ $offerwall->url }}" frameborder="0" width="100%" height="800px"></iframe>
    </div>
@endsection