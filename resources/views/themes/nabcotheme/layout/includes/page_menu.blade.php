<ul class="navbar-nav mr-auto">
    @foreach($items->get() as $item)
        <li class="nav-item">
            <a class="nav-link" href="{!! url($item->slug) !!}">{{$item->title}}</a>
        </li>
    @endforeach
</ul>