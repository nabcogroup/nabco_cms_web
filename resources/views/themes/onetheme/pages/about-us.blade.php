@extends('theme::layout.master')

@section('content')

<div class="container">
    <div class="page-header">
        <h1>{{$page->title}}</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            {!! $page->body !!}
        </div>
    </div>
</div>

@endsection