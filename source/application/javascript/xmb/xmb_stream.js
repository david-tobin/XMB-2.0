function status_post(status) {
	$(document).ready(function() {
		$.ajaxSetup( {
			url : siteurl + "/ajax/post_stream_status/",
			global : false,
			type : "POST"
		});
		
		var data = "status=" + status + "&securitytoken=" + securitytoken;

		$.ajax( {
			type : "POST",
			data : data,
			success : function(msg) {
				if (msg != '') {
					$("#streamholder").fadeOut(1000);
					$("#streamholder").queue(function() {
						$(this).html(msg);
						$(this).dequeue();
					});
					$("#streamholder").fadeIn(2500);
				}
		}
		});
	});
}

function refreshStream() {
	$(document).ready(function() {
		$.ajaxSetup( {
			url : siteurl + "/ajax/refreshStream/",
			global : false,
			type : "POST"
		});
		
		var data = "securitytoken=" + securitytoken;

		$.ajax( {
			type : "POST",
			data : data,
			success : function(msg) {
				if (msg != '') {
					$("#streamholder").fadeOut(1000);
					$("#streamholder").queue(function() {
						$(this).html(msg);
						$(this).dequeue();
					});
					$("#streamholder").fadeIn(2500);
				}
		}
		});
	});
}