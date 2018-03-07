<div class="container-fluid">
    <div class="row">
        @foreach($dataTypeContent as $data)
            <div class="col-md-2">
                <a href="" class="js-media-manager-thumbnail">
                    <img src="@if(!filter_var($data->thumbnail_path,FILTER_VALIDATE_URL)){{Voyager::image($data->thumbnail_path)}} @else {{$data->thumbnail_path}} @endif " class="img-thumbnail" alt="">
                </a>
            </div>
        @endforeach
    </div>
</div>