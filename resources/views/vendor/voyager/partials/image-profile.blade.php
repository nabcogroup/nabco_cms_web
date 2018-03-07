<img id="$id"
     src="@if(!filter_var($displayOptions,FILTER_VALIDATE_URL)) {{Voyager::image($path) }} @else {{ $path }}@endif"
     style="width:100%; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">