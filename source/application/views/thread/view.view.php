<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<script type="text/javascript"
	src="application/javascript/xmb/xmb_editor.js"></script>	
<script type="text/javascript"
	src="application/javascript/xmb/xmb_postrate.js"></script>	
			
<script type="text/javascript">
	function quickedit(id) {
		$(document).ready(function(){

				var message = $("#message_"+id).text();
				
				var edithtml = '<div id="editor_'+id+'"></div><br /><input class="input" type="submit" value="Save" />';		
				$("#message_"+id).html(edithtml);

				editorid = 'editor_'+id;
				editor.init(editorid, 'message', message, 'wysiwyg');
		});
	}
	
	$(function() {
		$("#triggers a").overlay({  
		    target: '#gallery', 
		    expose: '#f1f1f1',
		    top: 0
		}).gallery({ 
		    speed: 500 
		});
	});

</script>

<div class="head">{xmb:var thread.title}</div>
	<table class="xtable">		
		{xmb:html posts}
	</table>
	
<div class="simple_overlay" id="gallery"> 
 
    <a class="prev">prev</a> 
 

    <a class="next">next</a> 
 

    <div class="info"></div> 
 

    <img class="progress" src="http://static.flowplayer.org/tools/img/overlay/loading.gif" /> 
</div>	
	
{xmb:html menus}

{xmb:html footer}