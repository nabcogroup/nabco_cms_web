@if(!is_null($options))
    @foreach($options as $option)
        <div class="col-md-12">
            <div class="media">
                <div class="media-left">
                    <img class="media-object" id="$option->meta"
                    data-name="$option->meta"
                    class="js-img-galleries" 
                    src="@if(!filter_var($option->thumbnail,FILTER_VALIDATE_URL)) {{Voyager::image($option->thumbnail) }} @else {{ $option->thumbnail }}@endif" >    
                </div>
                <div class="media-body"></div>
            </div>
            <hr/>
        </div>
    @endforeach
@endif
