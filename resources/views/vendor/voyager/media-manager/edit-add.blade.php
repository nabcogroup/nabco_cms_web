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
            <form class="form-edit-add" role="form" action="
                        @if(isset($dataTypeContent->id)) {{ route('voyager.media-manager.update', $dataTypeContent->id) }}
            @else{{ route('voyager.media-manager.store') }}@endif"
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

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                                $excluded = ["thumbnail_path","full_path"];
                            @endphp
                            <!--  -->
                            @foreach($dataTypeRows as $row)
                            <!-- options -->
                                @php
                                    $options = json_decode($row->details);
                                    $display_options = isset($options->display) ? $options->display : NULL;
                                @endphp

                                @if ($options && isset($options->formfields_custom))
                                    @include('voyager::formfields.custom.' . $options->formfields_custom)
                                @else
                                    @if(!in_array($row->field,$excluded))
                                        <div class="form-group @if($row->type == 'hidden') hidden @endif @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}@else{{ '' }}@endif" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                            {{ $row->slugify }}
                                            <label for="name">{{ $row->display_name }}</label>
                                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-bordered panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Images</h3>
                        </div>
                        <div class="panel-body">
                            @php
                                $filterDataTypeRows = $dataTypeRows->whereIn('field',['thumbnail_path','full_path']);
                            @endphp
                            @foreach($filterDataTypeRows as $row)
                                <div class="form-group 
                                        @if($row->type == 'hidden') hidden @endif 
                                        @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}
                                        @else{{ '' }}
                                        @endif" 
                                        @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    
                                    <label for="name">{{ $row->display_name }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    
                                    <!-- browse image -->
                                    @if(!empty($dataTypeContent->{$row->field})) 
                                        <image 
                                            src="@if(!filter_var($dataTypeContent->{$row->field},FILTER_VALIDATE_URL)) {{Voyager::image($dataTypeContent->{$row->field}) }} @else {{ $dataTypeContent->{$row->field} }}@endif" 
                                            style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;"
                                        />
                                    @endif

                                    <!-- download image -->
                                    <input class="" type="file" name="img_{{$row->field}}" data-name="img_{{$row->field}}"/>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">
                        @if(isset($dataTypeContent->id)){{ "Update" }}@else <i class="icon wb-plus-circle"></i> {{ "Create New Media" }} @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop


@section('javascript')
<script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
            
            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });
        });
    </script>
@stop
