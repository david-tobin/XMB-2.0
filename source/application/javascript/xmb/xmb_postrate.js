function post_rate(pid, type) {
	$(document).ready(
			function() {

				$.ajaxSetup( {
					url : siteurl + "/ajax/post_rate/" + type,
					global : false,
					type : "POST"
				});

				var data = "postid=" + pid + "&securitytoken=" + securitytoken;
				var rating = parseFloat($("#rating_" + pid).text());

				$.ajax( {
					type : "POST",
					data : data,
					success : function(msg) {
						if (msg == 'positive') {
							if (rating + 1 == 0) {
								$("#ratings_" + pid)
										.css('background-color', '#5f5f5f');
							} else if (rating + 1 > 0) {
								$("#ratings_" + pid).css('background-color', 'green');
							} else if (rating + 1 < 0) {
								$("#ratings_" + pid).css('background-color', 'red');
							}

							$("#rating_" + pid).text(rating + 1);

							var ratingnow = $("#rating_" + pid).text();

							if (rating == 0) {
								$("#rating_" + pid).text("+" + ratingnow);
							}

							$("#postrateg_" + pid).remove();
							$("#postrateb_" + pid).remove();
							$("#rate_message_" + pid).text(
									'Thank you for rating!');
						} else if (msg == 'negative') {
							if (rating - 1 == 0) {
								$("#ratings_" + pid).css('background-color', '#5f5f5f');
							} else if (rating - 1 > 0) {
								$("#ratings_" + pid).css('background-color', 'green');
							} else if (rating - 1 < 0) {
								$("#ratings_" + pid).css('background-color', 'red');
							}

							$("#rating_" + pid).text(rating - 1);

							$("#postrateg_" + pid).remove();
							$("#postrateb_" + pid).remove();
							$("#rate_message_" + pid).text(
									'Thank you for rating!');
						}
					}
				});
			});
}