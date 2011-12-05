$(document).ready(function(){
	$("#sidebar-button").click(function(){
		
		$("#sidebar-dialog").dialog({
			bgiframe: true,
			resizable: false,
			draggable: false,
			hide: 'slide',
			show: 'slide',
			height:140,
			modal: true,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			},
			buttons: {
				'Globally': function() {
				$.ajaxSetup({
					  url: siteurl+"/ajax/sidebar_status/1/",
					  global: false,
					  type: "POST"
					});

				var data = "securitytoken="+securitytoken; 

				 $.ajax({
					   type: "POST",
					   data: data,
					   success: function(msg){
					   	if (msg != null)
					   	{
					   		$("#sidebar").fadeOut("slow");
					   		$(this).dialog('close');
					   		$("#sidebar-dialog2").dialog({
								bgiframe: true,
								resizable: false,
								draggable: false,
								hide: 'slide',
								show: 'slide',
								height:140,
								modal: true,
								overlay: {
									backgroundColor: '#000',
									opacity: 0.5
								},
								buttons: {
									Close: function() {
										$(this).dialog('close');
										window.location.reload();
									}
								}
							});
					   	}
					}
					 });
				},
				'This Page': function() {
					$.ajaxSetup({
						  url: siteurl+"/ajax/sidebar_status/2/",
						  global: false,
						  type: "POST"
						});

					var data = "securitytoken="+securitytoken+"&page="+controller; 

					 $.ajax({
						   type: "POST",
						   url: siteurl+"/ajax/sidebar_status/2/",
						   data: data,
						   success: function(msg){
						   	if (msg != null)
						   	{
						   		$("#logo").text = msg;
						   		$("#sidebar").fadeOut("slow");
						   		$(this).dialog('close');
						   		$("#sidebar-dialog2").dialog({
									bgiframe: true,
									resizable: false,
									draggable: false,
									hide: 'slide',
									show: 'slide',
									height:140,
									modal: true,
									overlay: {
										backgroundColor: '#000',
										opacity: 0.5
									},
									buttons: {
										Close: function() {
											$(this).dialog('close');
											window.location.reload();
										}
									}
								});
						   		
						   		
						   	}
						}
					});
				}
			}
		});
	});
});	