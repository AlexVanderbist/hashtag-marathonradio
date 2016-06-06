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

	<div class="container text-center" id="mainContainer">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<img src="{{asset("img/logo.png")}}" alt="Marathonradio" id="logo">
				<p>De onofficiële #marathonradio statistieken!</p>

				<div>
					<button type="button" class="btn btn-sm btnToggleTweetCol" id="btnEnableTweetCol">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Live #marathonradio tweets weergeven
					</button>
					<button type="button" class="btn btn-sm btnToggleTweetCol" id="btnDisableTweetCol" style="display:none">
						<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Live #marathonradio tweets verbergen
					</button>
				</div>

				<small>Teller loopt sinds 5 juni 2016 en vernieuwt elke minuut.<br/>Laatste update om <span id="lastRefresh"></span></small>

				<button class="btn btn-primary btn-sm hidden" id="reloadBtn"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> vernieuwen</button>
			</div>
		</div>
		<div class="row" style="margin-top:20px;">
			<div class="col-md-4 resizeColumn">
				<div class="well well-sm">
					<h1 id="totalTweetCount">Laden...</h1>
					<h3>tweets met #marathonradio</h3>
				</div>
				<div class="well well-sm">
					<h1 id="totalUserCount">Laden...</h1>
					<h3>twitteraars posten met #marathonradio</h3>
				</div>
				<div class="well well-sm">
					<h1 id="tpm">Laden...</h1>
					<h3>tweets per minuut</h3>
					<small>Da's <span id="tps">0</span> tweets per seconde!</small>
					<canvas id="tpmChart" width="400" height="250"></canvas>
				</div>
			</div>
			<div class="col-md-4 resizeColumn">
				<div class="well well-sm">
					<div class="profileImg julie"></div>
					<h1 id="julieCount">Laden...</h1>
					<h3>tweets tijdens Julie</h3>
				</div>
				<div class="well well-sm">
					<div class="profileImg tom"></div>
					<h1 id="tomCount">Laden...</h1>
					<h3>tweets tijdens Tom</h3>
				</div>
				<div class="well well-sm">
					<div class="profileImg peter"></div>
					<h1 id="peterCount">Laden...</h1>
					<h3>tweets tijdens Peter</h3>
				</div>
				<div class="well well-sm">
					<h2 style="margin-bottom:15px;font-weight:bold;">Meest voorgekomen woorden</h2>
					<ul class="list-group scrollable scrollable-sm text-left" id="wordOccurences">
					</ul>
				</div>
			</div>
			<div class="col-md-4 resizeColumn">
				<div class="well well-sm">
					<h2 style="margin-bottom:15px;font-weight:bold;">Meeste tweets met #marathonradio</h2>


					<input type="text" id="search" class="form-control" placeholder="Zoek jou @twitter">
					<div id="searchResult" style="display:none;">
						<div>
							<div class="profileImg"></div>
							<h1><span id="searchPos"></span> plaats!</h1>
							<div class="clearfix"></div>
						</div>
						<h3>Proficiat! Deel je overwinning:</h3>
						<div id="twitterBtn"></div>
					</div>
					<div id="noResults" style="display:none;">
						<h3>Helaas, je staat niet in de top 500 :(</h3>
					</div>


					<ul style="margin-top:15px;" class="list-group scrollable text-left" id="usersWithMostHashtags">
					</ul>
				</div>
			</div>



			<div class="col-md-3" id="tweetCol" style="display:none;">
				<div class="well well-sm">
					<h1>Live tweets</h1>
					<small>#marathonradio, @MNMbe, @petervandeveire, @tomdecock en @julievdsteen</small>
					<a class="twitter-timeline"
						href="https://twitter.com/search?q=%23marathonradio%20OR%20%40mnmBE%20OR%20%40petervandeveire%20OR%20%40tomdecock%20OR%20%40julievdsteen"
						data-widget-id="739618451384307712"
            			data-chrome="nofooter noheader noborders transparent"
						>
						Tweets over #marathonradio OR @mnmBE OR @petervandeveire OR @tomdecock OR @julievdsteen
					</a>
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

	<script>
		window.twttr = (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0],
			t = window.twttr || {};
			if (d.getElementById(id)) return t;
			js = d.createElement(s);
			js.id = id;
			js.src = "https://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);

			t._e = [];
			t.ready = function(f) {
				t._e.push(f);
			};

			return t;
		}(document, "script", "twitter-wjs"));
	</script>

	<script src="{{asset("js/vendor.js?v=1")}}"></script>
	<script src="{{asset("js/app.js?v=1")}}"></script>

	<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-78767994-1', 'auto');
	  ga('send', 'pageview');

	</script>
</body>

</html>
