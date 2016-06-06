$(function() {

	var ctx = document.getElementById("tpmChart");
	var tpmChart = new Chart(ctx, {
	    type: 'line',
	    data: {
			labels: ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
			datasets: [
				{
		            fill: true,
		            lineTension: 0.3,
		            backgroundColor: "rgba(26, 152, 204, 0.4)",
		            borderColor: "#12367E",
		            borderCapStyle: 'butt',
		            borderDash: [],
		            borderDashOffset: 0.0,
		            borderJoinStyle: 'miter',
		            pointRadius: 0,
					data: [0,0,0,0,0,0,0,0,0,0]
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
						suggestedMax: 10
	                }
	            }],
	        }
	    }
	});

	setInterval(loadNewData, 10000);
	setInterval(loadTweetsPerSchedule, 5000);

	function loadTweetsPerSchedule() {
		$.get("/tweets-per-schedule", function(data) {
			//date.tweetsPerSchedule

			// Tweets per minute graph
			$.each(data.schedules, function(key, value) {
				tpmChart.data.datasets[0].data[data.schedules.length-1 - key] = value.num_new_tweets;
			});
			tpmChart.update();
		});
	}

	function loadNewData () {
		console.log('loading new data');
		$.get("/data", function(data) {
			$('#totalTweetCount').html(data.totalTweetCount);
			$('#tps').html(parseFloat(data.tps).toFixed(2));
			$('#tpm').html(data.tpm);
			$('#totalUserCount').html(data.totalUserCount);
			$('#peterCount').html(data.tweetsPerPerson.peter);
			$('#tomCount').html(data.tweetsPerPerson.tom);
			$('#julieCount').html(data.tweetsPerPerson.julie);


			// Users with most hashtags
			$('#usersWithMostHashtags').empty();
			$.each(data.usersWithMostHashtags, function( key, value ){
				var $newLi = $('<li/>')
							.addClass('list-group-item');
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
							.html(value.word);

				$('#wordOccurences').append($newLi);
			});

			// Last refresh
			moment.locale('nl');
			$('#lastRefresh').html(moment().format('LTS'));
		});
	}

	loadNewData();

	$('#reloadBtn').on("click", loadNewData);

	// Tweetbar code
	var displayTweetCol = false;
	$('.btnToggleTweetCol').on("click", function(){
		if(displayTweetCol) {
			$('#mainContainer').toggleClass('container-fluid container');
			$('#tweetCol').hide();
			$('#btnDisableTweetCol').hide();
			$('#btnEnableTweetCol').show();
			$('.resizeColumn').removeClass('col-md-3').addClass('col-md-4');
			displayTweetCol = false;
		} else {
			// show Tweetbar
			$('#mainContainer').toggleClass('container-fluid container');
			$('#btnDisableTweetCol').show();
			$('#btnEnableTweetCol').hide();
			$('.resizeColumn').removeClass('col-md-4').addClass('col-md-3');
			$('#tweetCol').show();
			displayTweetCol = true;
		}
	});

});
