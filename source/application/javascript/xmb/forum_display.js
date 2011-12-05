function edit_title(id)
{
	$(document).ready(function(){
			var title = $("#subject_"+id).text();

			$("#subject_"+id).html('<form onsubmit="post_title('+id+');return false;"><input class="input" type="text" size="50" name="title_"'+id+' id="title_'+id+'" value="'+title+'" /></form>');
			$("#area_"+id).removeAttr('ondblclick');
	});
}

function post_title(id)
{
	$(document).ready(function(){
		var newtitle = $("#title_"+id).attr('value');

		$.ajaxSetup({
			  url: siteurl+"/ajax/edittitle/",
			  global: false,
			  type: "POST"
			});

		var data = "title="+newtitle+"&securitytoken="+securitytoken+"&tid="+id;

		 $.ajax({
			   type: "POST",
			   data: data,
			   success: function(msg){
			   	if (msg == 'success')
			   	{
			     $("#subject_"+id).html('<a href="'+siteurl+'/thread/view/'+id+'/">'+newtitle+'</a>');
				 $("#area_"+id).attr('ondblclick', 'edit_title('+id+')');
			   	}
			}
			 });

									
	});
}

function delete_thread(id, fid)
{
	$(document).ready(function(){
	
			$.ajaxSetup({
				  url: siteurl+"/ajax/deletethread/",
				  global: false,
				  type: "POST"
				});
	
			var data = "tid="+id+"&fid="+fid+"&securitytoken="+securitytoken;
			 $.ajax({
				   type: "POST",
				   data: data,
				   url: siteurl+"/ajax/deletethread/",
				   success: function(back) {
				   	if (back == 'success')
				   	{
				   		$("#thread_"+id).fadeOut("slow");
				   	}
				}
				 });
	});
}

function mod_tools(id) {
    $(document).ready(function(){
        
        $("#modtools_"+id).overlay({

        	// one configuration property
        	color: '#ccc',
        
        	// another property
        	top: 50
        
        	// ... the rest of the configuration properties
        });
        
    });
}