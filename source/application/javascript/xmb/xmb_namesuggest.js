function namesuggest(div, parent) {
	$(document).ready(function() {
		$.ajaxSetup( {
			url : siteurl + "/ajax/namesuggest/",
			global : false,
			type : "POST"
		});
		
		var name = $("#"+parent).attr('value');
		var data = "username=" + name + "&securitytoken=" + securitytoken;

		$.ajax( {
			type : "POST",
			data : data,
			success : function(msg) {
				if (msg != '') {
					var offset = $("#"+parent).offset();
					
					$("#" + div).fadeIn();
					$("#" + div).html(msg);
					$("#" + div).css( {
						"left" : offset.left,
						"top" : offset.top + 24
					});
					
					$("#" + parent).bind('blur', function() {
						$("#" + div).fadeOut();
					});
				} else {
					$('#' + div).fadeOut();
			}
		}
		});
	});
}