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

	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ asset("css/app.css") }}">
</head>

<body>
	<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

	<div class="container text-left" id="mainContainer">
		<div class="row">
			<div class="col-sm-3 text-center">
				<img src="{{asset("img/logo.png")}}" alt="Marathonradio" id="logo">
			</div>


			<div class="col-sm-9">

				<h1>De onofficiële #marathonradio statistieken!</h1>

				<h3>Dag <span id="dayCount"></span> van Marathonradio! <small>(speciaal voor Tom ;)</small></h3>


				<p>Teller loopt sinds 4 juni 2016 en vernieuwt elke minuut.<br/>Laatste update om <span id="lastRefresh"></span></p>
			</div>
		</div>
	</div>

	<div class="container text-center">
		<div class="row">
			<div class="col-md-3">

				<div class="panel panel-default">
					<div class="panel-body">
						<h1><i class="fa fa-bomb" aria-hidden="true"></i> <span id="tpm">Laden...</span></h1>
						<h3>tweets per minuut</h3>
						<small>Da's <span id="tps">0</span> tweets per seconde!</small>
						<canvas id="tpmChart" width="400" height="250"></canvas>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body text-left">

						<div class="djStat">
							<div class="profileImg julie"></div>
							<h2 id="julieCount">Laden...</h2>
							<h3>tweets tijdens Julie</h3>
							<div class="clearfix"></div>
						</div>

						<div class="djStat">
							<div class="profileImg tom"></div>
							<h2 id="tomCount">Laden...</h2>
							<h3>tweets tijdens Tom</h3>
							<div class="clearfix"></div>
						</div>

						<div class="djStat">
							<div class="profileImg peter"></div>
							<h2 id="peterCount">Laden...</h2>
							<h3>tweets tijdens Peter</h3>
							<div class="clearfix"></div>
						</div>

					</div>
				</div>

			</div>

			<div class="col-md-3">

				<div class="panel panel-default">
					<div class="panel-body">
						<h1><i class="fa fa-hashtag" aria-hidden="true"></i> <span id="totalTweetCount">Laden...</span></h1>
						<h3>tweets met #marathonradio</h3>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<h2 id="winningTweetHeader">Wie wordt de 100.000ste tweet?</h2>
						<div id="randomTweet" style="position:relative; overflow: hidden" class="text-left">

						</div>
						<div id="winningTweet" style="display:none;">
							<h2>100.000ste tweet</h2>
							<div class="text-left">
								<div class="profileImg pull-left"></div>
								<h3 class="username"></h3>
								<span class="tweet"></span>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<div id="winningTweet">
							<h2>50.000ste tweet</h2>
							<div class="text-left">
								<div class="profileImg pull-left" style="width: 50px; height: 50px; margin: 0px 10px 10px 0px; background-image: url('img/jo.jpg');"></div>
								<h3 class="username">@ImjoOfficial</h3>
								<span class="tweet">@julievdsteen I LOVE YOU #marathonradio</span>
							</div>
						</div>
					</div>
				</div>

			</div>


			<div class="col-md-3">

				<div class="panel panel-default">
					<div class="panel-body">
						<h1><i class="fa fa-twitter" aria-hidden="true"></i> <span id="totalUserCount">Laden...</span></h1>
						<h3>twitteraars met #marathonradio</h3>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<h2 style="margin-bottom:15px;font-weight:bold;">Meeste tweets</h2>


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
					</div>
					<ul style="margin-top:15px;" class="list-group scrollable scrollable-sm text-left" id="usersWithMostHashtags">
					</ul>
				</div>
			</div>

			<div class="col-md-3">

				<div class="panel panel-default">
					<div class="panel-body">
						<h2><i class="fa fa-heart" aria-hidden="true"></i> Populairste woorden</h2>
						<h3>(laatste 30 minuten)</h3>
					</div>
					<ul class="list-group scrollable scrollable-sm text-left" id="wordOccurencesLastTenMinutes">
					</ul>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<h2>Meest gebruikte woorden</h2>
					</div>
					<ul class="list-group scrollable scrollable-sm text-left" id="wordOccurences">
					</ul>
				</div>

			</div>
		</div>
	</div>
	<!-- /container -->

	<footer>
		<div class="container text-center">
			<small>
				Gemaakt door <a target="_blank" href="https://twitter.com/AlexVanderbist">&commat;AlexVanderbist</a><br />
				{{-- ❤️ <a href="https://www.facebook.com/groups/1707371486189381/">#marathonradioSquad</a><br/> --}}
				Niet officieel geassocieerd met MNM.<br/>
				Copyright &copy; Het MNM &amp; Marathonradio logo zijn eigendom van MNM.
			</small>
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

	<script src="{{asset("js/vendor.js?v=2")}}"></script>
	<script src="{{asset("js/animatedListUpdate.js")}}"></script>
	<script src="{{asset("js/app.js?v=3")}}"></script>

	<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-78767994-1', 'auto');
		ga('send', 'pageview');

		setInterval(function() {
			ga('send', 'event', 'HeartBeat', '300s');
		}, 300000);

	</script>
</body>

</html>
