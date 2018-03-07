@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager.generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager.generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form action="@if(isset($dataTypeContent->id)){{ route('voyager.pages.update') }}@else {{ route('voyager.pages.store')  }}@endif"
                          method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if(isset($dataTypeContent->id))
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $options = json_decode($row->details);
                                    $display_options = isset($options->display) ? $options->display : NULL;
                                @endphp

                                @if ($options && isset($options->formfields_custom))
                                    @include('voyager::formfields.custom.' . $options->formfields_custom)
                                @else
                                    @if($row->field == 'meta_media')
                                        <div class="form-group">
                                            {{ $row->slugify }}
                                            <label for="name">{{ $row->display_name }}</label>
                                            <textarea name="{{$row->field}}"  class="form-control">{!! $dataTypeContent->meta_media !!}</textarea>
                                            <a href="#" id="meta_media_browser" class="btn btn-default">Open Media</a>
                                        </div>
                                    @else
                                        <div class="form-group @if($row->type == 'hidden') hidden @endif @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}@else{{ '' }}@endif" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                            {{ $row->slugify }}
                                            <label for="name">{{ $row->display_name }}</label>
                                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                                            @if($row->type == 'relationship')
                                                @include('voyager::formfields.relationship')
                                            @else
                                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                            @endif

                                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


@section('javascript')
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script>
        (function() {
            $("#meta_media_browser").on("click",function(e) {
                e.preventDefault();

                var dialog = bootbox.dialog({
                    title: "Media Manager",
                    message: "This is an alert with a callback!",
                    size: 'large',
                    closeButton: true
                });

                dialog.init(function() {
                    setTimeout(function() {

                        var options = {
                            url:"@php echo route('voyager.media-manager.index') @endphp"
                        };

                        $.get(options,function(data) {
                            dialog.find('.bootbox-body').html( data );

                            //piece of code
                            var mediaThumbnail = dialog.find('.js-media-manager-thumbnail');
                            if(mediaThumbnail.length > 0) {
                                mediaThumbnail.on("click",function(e) {

                                });
                            }
                        });
                    })
                });
            });
        }());
    </script>
@stop