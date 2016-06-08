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
	//setInterval(loadTweetsPerMinute, 5000);

	loadNewData();
	//loadTweetsPerMinute();

	numeral.language('nl', {
        delimiters: {
            thousands: ' ',
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
			$(this)
				.prop('number', (isNaN($(this).html()) ? 0 : $(this).html()))
				.animateNumber({number: num}, 5000);
	    });
	};


	var usersWithMostHashtags = {};
	function loadNewData () {

		console.log('loading new data');
		$.get("/data", function(data) {

			// Random numbers
			$('#totalTweetCount').setElementNumber(data.totalTweetCount);
			$('#tps').setElementNumber(parseFloat(data.tps).toFixed(2));
			$('#tpm').setElementNumber(data.tpm);
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
							.html((key+1) + '. ' + value.word);

				$('#wordOccurences').append($newLi);
			});

			// Last refresh
			moment.locale('nl');
			$('#lastRefresh').html(moment().format('LTS'));
		});
	}


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

	function updateTwitterValues(share_url, title) {
		// clear out the <a> tag that's currently there...probably don't really need this since you're replacing whatever is in there already.
		$("#twitterBtn").html('&nbsp;');
		$("#twitterBtn").html('<a href="https://twitter.com/share" class="twitter-share-button" data-url="' + share_url +'" data-size="large" data-text="' + title + '" data-count="none">Tweet</a>');
		window.twttr.widgets.load();
	}

	// Search
	$( "#search" ).keyup(function( event ) {

		//a = [{prop1:"abc",prop2:"qwe"},{prop1:"bnmb",prop2:"yutu"},{prop1:"zxvz",prop2:"qwrq"}]
		var indexes = $.map(usersWithMostHashtags, function(obj, index) {
		    if(obj.username == $('#search').val()) {
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
	});
});
