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

	setInterval(loadNewData, 5000);
	setInterval(loadTweetsPerMinute, 1000);

	function loadTweetsPerMinute() {
							tpmChart.data.datasets[0].data.push(Math.random() * 3);
				tpmChart.data.datasets[0].data.shift();
	//			tpmChart.data.datasets[0].data.push(data.tpm);
				tpmChart.update(0);
		$.get("/tpm", function(data) {
			//date.tweetsPerSchedule

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
