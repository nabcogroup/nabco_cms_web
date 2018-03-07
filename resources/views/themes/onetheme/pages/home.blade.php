
@extends('theme::layout.master') 

@section('content')

<div class="view hm-white-light jarallax" data-jarallax='{"speed": 0.2}' data-jarallax-video="https://www.youtube.com/watch?v=QufJ0ctfZG0">
	<div class="full-bg-img">
		<div class="flex-center">
			<div class="container">
				<div class="row">
					<!-- <div class="col-md-12 wow fadeIn">
						<div class="text-center text-danger">
							<h1 class="display-2 mb-2 wow fadeInDown" data-wow-delay="0.3s">Nabco Furniture</h1>
							<h5 class="font-up mb-3 mt-1 font-bold wow fadeInDown" data-wow-delay="0.4s">Welcome to Nabco Furniture Web</h5>
							<a class="btn btn-danger btn-lg wow fadeInDown" data-wow-delay="0.4s">
								<i class="fa fa-diamond"></i> Continue Shop</a>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		
		@php
			
			$posts = get_post_by_category("category-1");

		@endphp
		
		@foreach($posts as $post)
			<article class="col-md-12">
				<div class="page-header">
					<h3>{{$post->title}}</h3>
				</div>
				<div class="content">
					{!! $post->body !!}
				</div>
			</article>
		@endforeach

	</div>

	<div>
		
		@include("theme::partials.partial-products")

	</div>
</div>

@endsection @section('css')
<style>
	.whov:hover {
		background-color: #00695c!important;
	}

	.view {
		background-position: center center;
		background-repeat: no-repeat;
		height: 500px;
	}

	.secondbase {
		background-color: rgba(255, 255, 255, .6);
		margin-top: -90px;
	}

	.full-bg-img {
		position: relative;
	}

	.flex-center {
		position: absolute;
		top: 120px;
		left: 180px;
	}
</style>
@endsection



