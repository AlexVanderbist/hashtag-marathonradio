<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="nl">
<!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>#marathonradio</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">

	<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="{{ asset("css/app.css") }}">
</head>

<body>
	<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

	<div class="container">
		<div class="text-center">
			<img src="{{asset("img/logo.png")}}" alt="Marathonradio" id="logo">
			<p>De onofficiële #marathonradio statistieken!</p>
			<small>Teller loop sinds 5 juni 2016 en vernieuwt elke minuut.</small>
		</div>
		<div class="row" style="margin-top:20px;">
			<div class="col-md-4 text-center">
				<div class="well">
					<h1>{{$totalTweetCount}}</h1>
					<h3>tweets met #marathonradio</h3>
				</div>
			</div>

			<div class="col-md-4 col-md-push-4 text-center">
				<div class="well">
					<h1>{{$totalUserCount}}</h1>
					<h3>twitteraars posten met #marathonradio</h3>
				</div>
			</div>

			<div class="col-md-4 col-md-pull-4">
				<div class="well">
					<h2 class="text-center" style="margin-bottom:15px;font-weight:bold;">Meeste tweets met #marathonradio</h2>
					<ul class="list-group scrollable">
						@foreach($usersWithMostHashtags as $user)
							<li class="list-group-item">
								<a target="_blank" href="https://twitter.com/{{$user->username}}">&commat;{{$user->username}}</a>
								<span class="badge">{{$user->count}} tweets</span>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- /container -->

	<footer>
		<div class="container text-center">
			<small>Gemaakt door <a target="_blank" href="https://twitter.com/AlexVanderbist">&commat;AlexVanderbist</a></small>
		</div>
	</footer>

	<script src="{{asset("js/app.js")}}"></script>

	<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
	<script>
		(function(b, o, i, l, e, r) {
			b.GoogleAnalyticsObject = l;
			b[l] || (b[l] =
				function() {
					(b[l].q = b[l].q || []).push(arguments)
				});
			b[l].l = +new Date;
			e = o.createElement(i);
			r = o.getElementsByTagName(i)[0];
			e.src = '//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e, r)
		}(window, document, 'script', 'ga'));
		ga('create', 'UA-XXXXX-X', 'auto');
		ga('send', 'pageview');
	</script>
</body>

</html>
