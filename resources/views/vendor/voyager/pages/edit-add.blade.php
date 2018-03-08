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
            <form id="frmPage"
                  action="@if(isset($dataTypeContent->id)){{ route('voyager.pages.update',$dataTypeContent->id) }}@else {{ route('voyager.pages.store')  }}@endif"
                  method="POST" enctype="multipart/form-data">
                <div class="col-md-8">
                    <div class="panel panel-bordered">


                        <!-- PUT Method if we are editing -->
                        @if(isset($dataTypeContent->id))
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- error -->
                            @include("voyager::partials.form-errors",['errors' => $errors])

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
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Image Featured</h3>
                        </div>
                        <div class="panel-body">
                            @php
                                $filterDataTypeRows = $dataTypeRows->whereIn('field',['meta_media']);
                            @endphp
                            @foreach($filterDataTypeRows as $row)
                                @php
                                    $options = json_decode($dataTypeContent->{$row->field});
                                    $displayOptions = (!is_null($options)) ? $options->thumbnail : null;
                                @endphp
                                <div class="col-md-12">
                                    <input type="hidden"  id="{{$row->field}}" name="{{$row->field}}" value="{{ $dataTypeContent->meta_media }}"/>
                                    @if(!is_null($displayOptions))
                                        @include("voyager::partials.image-profile",['id' => "metaimg", "path" => $displayOptions])
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <a href="#" id="meta_media_browser" class="btn btn-default">Open Media</a>
                                    <span id="meta-name">{{(!is_null($options)) ? $options->meta : "" }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">
                        @if(isset($dataTypeContent->id)){{ "Update" }}@else <i
                                class="icon wb-plus-circle"></i> {{ "Create Page" }} @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop


@section('javascript')
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="{{asset('core/js/media-manager.js')}}"></script>
    <script>
        (function () {
            $("#meta_media_browser").on("click", function (e) {
                e.preventDefault();

                var formPage = $("#frmPage");
                var dialog = bootbox.dialog({
                    title: "Media Manager",
                    message: "This is an alert with a callback!",
                    size: 'large',
                    closeButton: true
                });

                var options = {
                    url: "@php echo route('voyager.media-manager.index') @endphp"
                };

                MM.single(dialog,options)
            });
        }());
    </script>
@stop