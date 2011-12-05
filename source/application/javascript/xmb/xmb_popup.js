function xmb_popup(id, display) {
	$(document).ready(function(){
		$("#"+id).click(function(){
			$("#"+display).show();
		});
	});	
}