$(function() {

	setInterval(loadNewData, 60000);

	function loadNewData () {
		console.log('loading new data');
		$.get("/data", function(data) {
			$('#totalTweetCount').html(data.totalTweetCount);
			$('#tpm').html(data.tpm);
			$('#tps').html(data.tps);
			$('#totalUserCount').html(data.totalUserCount);

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
		});
	}

	loadNewData();

	$('#reloadBtn').on("click", loadNewData);

});
