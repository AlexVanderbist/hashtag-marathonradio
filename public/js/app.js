$(function() {

	var ctx = document.getElementById("tpmChart");
	var labels = new Array(60).fill('');
	labels[0] = '-5min';
	labels[12] = '-4min';
	labels[24] = '-3min';
	labels[36] = '-2min';
	labels[48] = '-1min';
	labels[59] = 'nu';
	var startData = new Array(60).fill(0);
	var tpmChart = new Chart(ctx, {
	    type: 'line',
	    data: {
			labels: labels,
			datasets: [
				{
		            fill: true,
		            lineTension: 0.4,
		            backgroundColor: "rgba(26, 152, 204, 0.4)",
		            borderColor: "#12367E",
		            borderCapStyle: 'butt',
		            borderDash: [],
		            borderDashOffset: 0.0,
		            borderJoinStyle: 'miter',
		            pointRadius: 0,
					data: startData,
				}
			],
		},
	    options: {
			legend: {
				display: false
			},
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true,
						suggestedMax: 5
	                }
	            }],
	            xAxes: [{
	                ticks: {
						maxTicksLimit: 10
	                }
	            }],
	        }
	    }
	});

	// Create new animated list
	$('#wordOccurencesLastTenMinutes').animatedListUpdate();


	setInterval(loadNewData, 5000);
	//setInterval(loadTweetsPerMinute, 5000);

	loadNewData();
	//loadTweetsPerMinute();

	numeral.language('nl', {
        delimiters: {
            thousands: '.',
            decimal  : ','
        },
        abbreviations: {
            thousand : 'k',
            million  : ' mln',
            billion  : ' mld',
            trillion : ' bln'
        },
        ordinal : function (number) {
            var remainder = number % 100;
            return (number !== 0 && remainder <= 1 || remainder === 8 || remainder >= 20) ? 'ste' : 'de';
        },
        currency: {
            symbol: '€ '
        }
    });
	numeral.language('nl');

	function loadTweetsPerMinute() {
		$.get("/tpm", function(data) {
			//date.tweetsPerSchedule

			//tpmChart.data.datasets[0].data.push(Math.random() * 3);
			tpmChart.data.datasets[0].data.push(data.tpm);
			tpmChart.data.datasets[0].data.shift();
			tpmChart.update(0);
		});
	}

	$.fn.setElementNumber = function(num){
	    this.each(function(){
			var dot_separator_number_step = $.animateNumber.numberStepFactories.separator('.');
			//console.log(isNaN($(this).html()), $(this).html().replace(/\./g,''));
			$(this)
				.prop('number', (isNaN($(this).html()) ? 0 : $(this).html().replace(/\./g,'')))
				.animateNumber({
					number: num,
    				numberStep: dot_separator_number_step
				}, 3000);
	    });
	};


	var totalTweetCount = 0;
	var confetti = false;
	var request;


	var usersWithMostHashtags = {};
	function loadNewData () {

		//console.log('loading new data');
		if(request) request.abort();
		request = $.get("/data", function(data) {

			if(data.forceRefresh) location.reload();

			// Random numbers
			$('#totalTweetCount').setElementNumber(data.totalTweetCount);
			$('#tps').html(parseFloat(data.tps).toFixed(2));
			$('#tpm').html(data.tpm);
			$('#totalUserCount').setElementNumber(data.totalUserCount);
			$('#peterCount').setElementNumber(data.tweetsPerPerson.peter);
			$('#tomCount').setElementNumber(data.tweetsPerPerson.tom);
			$('#julieCount').setElementNumber(data.tweetsPerPerson.julie);

			// tpm
			tpmChart.data.datasets[0].data.push(data.tpm);
			tpmChart.data.datasets[0].data.shift();
			tpmChart.update(0);


			// Users with most hashtags
			$('#usersWithMostHashtags').empty();
			usersWithMostHashtags = data.usersWithMostHashtags;
			$.each(data.usersWithMostHashtags, function( key, value ){
				var $newLi = $('<li/>')
							.addClass('list-group-item')
							.html((key+1) + '. ');
				var $newA = $('<a/>')
							.attr("href", "http://www.twitter.com/"+value.username)
							.attr("target", "_blank")
							.html(value.username);
				var $newSpan = $('<span/>')
							.addClass('badge')
							.html(value.count + ' tweets');

				$newLi.append($newA);
				$newLi.append($newSpan);

				$('#usersWithMostHashtags').append($newLi);
			});

			// Most occuring words
			$('#wordOccurences').empty();
			$.each(data.wordOccurences, function( key, value ){
				var $newLi = $('<li/>')
								.addClass('list-group-item')
								.text((key+1) + '. ' + value.word)
							.append($('<span/>',{class:'badge'})
								.text(value.occurences + ' tweets'));

				$('#wordOccurences').append($newLi);
			});

			// Most occuring words last 10 minutes
			var wordOccurencesLastTenMinutesArray = [];
			$.each(data.wordOccurencesLastTenMinutes, function (index,value) {
				$append = $('<span/>')
								.text((index+1) + '. ' + value.word)
							.add($('<span/>',{class:'badge'})
								.text(value.occurences + ' tweets'));
				wordOccurencesLastTenMinutesArray.push({
					id: value.word,
					$liAppend: $append
				});
			});
			$('#wordOccurencesLastTenMinutes').data('animatedListUpdate').updateList(wordOccurencesLastTenMinutesArray);


			// 50000 tweets
			if(totalTweetCount != data.totalTweetCount && !data.winningTweet) {
				$('#randomTweet > div').animate({left:-300, opacity:0},1000, function() {
					$(this).remove();
				});
				$newTweet = $('<div/>')
								.css({'position': 'absolute', 'left': '300px', 'top' : '0px', 'width': '100%', 'opacity':0})
								.append(
									$('<p/>')
										.text(data.lastTweet.tweet)
										.prepend(
											$('<h4/>')
												.text('Tweet ' + numeral(data.totalTweetCount).format('0,0') +  ': @'+data.lastTweet.username)
												.css('font-weight', 'bold')));

				$('#randomTweet').append($newTweet);
				$newTweet.animate({left:'0', opacity:1},1500);
				$('#randomTweet').animate({height: $newTweet.outerHeight()}, 500);
			} else if(data.winningTweet) {
				// winning tweet is chosen
				$('#randomTweet').hide();
				$('#winningTweetHeader').hide();
				$winningTweet = $('#winningTweet');
				$winningTweet.find('.tweet').text(data.winningTweet.tweet);
				$winningTweet.find('.username').text('@'+data.winningTweet.username);
				$winningTweet.find('.profileImg').css({'background-image': 'url('+ data.winningTweet.image+ ')', 'width':"70px", 'height':"70px", 'margin': '0 15px 15px 0'});
				$winningTweet.show();

				if(confetti === false) {
					var myCustomEvent = new Event('winningEvent');
					document.dispatchEvent(myCustomEvent);
					confetti = true;
				}

			}
			totalTweetCount = data.totalTweetCount;


			// Last refresh
			moment.locale('nl');
			$('#lastRefresh').html(moment().format('LTS'));

			var a = moment("2016-06-04");
			var b = moment();
			var diff = b.diff(a, 'days'); // 1
			$('#dayCount').html(diff + 1);
		});
	}


	$('#reloadBtn').on("click", loadNewData);

	// // Tweetbar code
	// var displayTweetCol = false;
	// $('.btnToggleTweetCol').on("click", function(){
	// 	if(displayTweetCol) {
	// 		$('#mainContainer').toggleClass('container-fluid container');
	// 		$('#tweetCol').hide();
	// 		$('#btnDisableTweetCol').hide();
	// 		$('#btnEnableTweetCol').show();
	// 		$('.resizeColumn').removeClass('col-md-3').addClass('col-md-4');
	// 		displayTweetCol = false;
	// 	} else {
	// 		// show Tweetbar
	// 		$('#mainContainer').toggleClass('container-fluid container');
	// 		$('#btnDisableTweetCol').show();
	// 		$('#btnEnableTweetCol').hide();
	// 		$('.resizeColumn').removeClass('col-md-4').addClass('col-md-3');
	// 		$('#tweetCol').show();
	// 		displayTweetCol = true;
	// 	}
	// });

	function updateTwitterValues(share_url, title) {
		// clear out the <a> tag that's currently there...probably don't really need this since you're replacing whatever is in there already.
		$("#twitterBtn").html('&nbsp;');
		$("#twitterBtn").html('<a href="https://twitter.com/share" class="twitter-share-button" data-url="' + share_url +'" data-size="large" data-text="' + title + '" data-count="none">Tweet</a>');
		window.twttr.widgets.load();
	}

	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();

	// Search
	$( "#search" ).keyup(function( event ) {

		delay(function(){


			var indexes = $.map(usersWithMostHashtags, function(obj, index) {
			    if(obj.username.toLowerCase() == $('#search').val().toLowerCase()) {
			        return index;
			    }
			});

			var index = indexes[0];
			//var index = usersWithMostHashtags.findIndex(x => x.username==$('#search').val());
			var user = usersWithMostHashtags[index];
			if (user) {
				$('#noResults').hide();
				$('#searchResult .profileImg').css('background-image', 'url(' + user.image + ')');
				updateTwitterValues('http://www.hashtagmarathonradio.be/', "Ik sta op de "+numeral(index+1).format('0o')+" plaats in de #marathonradio top 500!");
				//$('.twitter-share-button').attr('href', "https://twitter.com/intent/tweet?text=" + escape("Ik sta op de "+(index+1)+" plaats in de #marathonradio top 500!"));
				$('#searchPos').html(numeral(index+1).format('0o'));
				$('#searchResult').show();
			} else {
				$('#searchResult').hide();
				if($('#search').val().length) $('#noResults').show();
				else $('#noResults').hide();
			}


	    }, 1000 );
	});



});
