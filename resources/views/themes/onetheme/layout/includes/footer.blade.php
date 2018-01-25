<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
                    <div class="link-area">
                        <h4>Site Map</h4>
                        {{menu('main-menu','theme::layout.includes.main_menu')}}
                    </div>
				</div>
				<div class="col-md-3">
					<h4>Our Products</h4>
				</div>
				<div class="col-md-3">
					<h4>Subsidiaries</h4>
					<ul>
					@foreach($products as $product) 
						<li><a href="">{{$product->title}}</a></li>
					@endforeach
					</ul>
				</div>
				<div class="col-md-3">
					<h4>Contact Details</h4>
				</div>
			</div>
		</div>
</footer>