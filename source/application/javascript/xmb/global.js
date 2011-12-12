$(document).ready(function(){

	$("#"+current).attr('id', 'current');
	
});

function status_post(status) {
	$(document).ready(function() {
        var method = (action == 'stream') ? "1" : "0";
        
        var posturl = method == "1" ? (siteurl + '/ajax/post_stream_status/') : (siteurl + '/ajax/update_status/');
        
		$.ajaxSetup( {
			url : posturl,
			global : false,
			type : "POST"
		});
		
		var data = "status=" + status + "&securitytoken=" + securitytoken;

		$.ajax( {
			type : "POST",
			data : data,
			success : function(msg) {
				if (msg == true || msg.length > 3) {
				    if (action == 'stream') {
    					$("#streamholder").fadeOut(1000);
    					$("#streamholder").queue(function() {
    						$(this).html(msg);
    						$(this).dequeue();
    					});
    					$("#streamholder").fadeIn(2500);
                    }
                    
                    $("#sidebar_status").text(status);
                    $('#headstatus').attr('value', '');
                    $('#headstatus').blur();
				} else {
				    alert('Error occured while updating status.');
                    alert(msg);
				}
		}
		});
	});
}