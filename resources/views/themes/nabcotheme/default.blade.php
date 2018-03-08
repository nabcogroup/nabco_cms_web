@extends("theme::layout.master")

@section("content")
    @php
        $brands = get_post_by_category('brand');
    @endphp
    @foreach
        <div class="row">
            
        </div>
    @endforeach
@stop