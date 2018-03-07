
<div class="page-header">
    <h3>Our Products</h3>
</div>

@php 
    $products = get_post_by_category("product",["sort_order" => "asc"]);
@endphp

<div class="row">
    @foreach($products as $product)
        
        <div class="col-md-4">
            <div class="thumbnail">
                <img src="{{get_image_path($product->image)}}" />
                <div class="caption">
                    <h3>{{$product->title}}</h3>
                    <p>{!!$product->excerpt!!}</p>
                </div>
            </div>
        </div>
        
    @endforeach
</div>