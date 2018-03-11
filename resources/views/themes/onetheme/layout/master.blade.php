<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>{{setting('site.title')}}</title>

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
	 crossorigin="anonymous">

	<link href="{{theme_folder_url('/assets/css/app.css')}}" rel="stylesheet" />
	
	@yield("css")

</head>

<body>

	<header>
		<!-- top header -->
		<div id="top-header">
			<div class="container">
				<nav class="nb-menu-nav-right">
					{{menu('top-menu','theme::layout.includes.top_menu')}}
				</nav>
			</div>
		</div>
		<div id="main-logo">
			<div class="container">
				<a href="{!! url('/') !!}">
					<img class="logo" src="{{get_image_path(setting('site.logo'))}}" />
				</a>
			</div>
		</div>

		<div id="main-nav">
			<nav class="navbar navbar-inverse nb-navbar">
				<div class="container">
					<div class="collapse navbar-collapse">
						{{ menu('main-menu',"theme::layout.includes.main_menu") }}
					</div>
				</div>
			</nav>
		</div>
	</header>

	<main>
		@yield('content')
	</main>

	@include("theme::layout.includes.footer")

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://mdbootstrap.com/previews/docs/latest/js/bootstrap.min.js"></script>
    <script src="https://mdbootstrap.com/previews/docs/latest/js/mdb.min.js"></script>
    <script src="https://mdbootstrap.com/previews/docs/latest/js/jarallax.js"></script>
	<script src="https://mdbootstrap.com/previews/docs/latest/js/jarallax-video.js"></script>
	<script src="{{asset('js/chat-interface')}}"></script>
	<script>
			new WOW().init();
	</script> 
	@yield('scripts')
</body>

</html>