

    @php
        $thumbnail = $options->thumbnail ?? "";

    @endphp

    <img
        class="js-img-profile"
        src="@if(!filter_var($thumbnail,FILTER_VALIDATE_URL)) {{Voyager::image($thumbnail) }} @else {{ $thumbnail }}@endif"
        style="width:100%; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
