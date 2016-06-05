$(function() {

	var ctx = document.getElementById("tpmChart");
	var tpmChart = new Chart(ctx, {
	    type: 'line',
	    data: {
			labels: ['-10min', '','-8min','','-6min', '','-4min','','-2min',''],
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
	                    beginAtZero:true
	                }
	            }],
	        }
	    }
	});


	setInterval(loadNewData, 60000);

	function loadNewData () {
		console.log('loading new data');
		$.get("/data", function(data) {
			$('#totalTweetCount').html(data.totalTweetCount);
			$('#tps').html(parseFloat(data.tps).toFixed(2));
			$('#totalUserCount').html(data.totalUserCount);
			$('#peterCount').html(data.tweetsPerPerson.peter);
			$('#tomCount').html(data.tweetsPerPerson.tom);
			$('#julieCount').html(data.tweetsPerPerson.julie);
			$('#tpm').html(data.tpm[0].num_new_tweets);

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

			moment.locale('nl');
			$('#lastRefresh').html(moment().format('LTS'));

			$.each(data.tpm, function(key, value) {
				tpmChart.data.datasets[0].data[data.tpm.length-1 - key] = value.num_new_tweets;
			});
		    tpmChart.update();
		});
	}

	loadNewData();

	$('#reloadBtn').on("click", loadNewData);

});
