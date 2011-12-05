<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<script type="text/javascript"
	src="application/javascript/tiny_mce/jquery.tinymce.js"></script>	
	
<script type="text/javascript"
	src="application/javascript/xmb/xmb_namesuggest.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('#editor').tinymce({
		
		script_url : '{xmb:option site_url}/application/javascript/tiny_mce/tiny_mce.js',

		// General options
		theme : "advanced",
		plugins : "bbcode,safari,emotions,iespell,inlinepopups,media,print,contextmenu,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,tabfocus",

		theme_advanced_buttons1 : "bold,italic,underline,fontsizeselect,link,unlink,image",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : true,
		
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js"
	});
});
</script>
<form action="messages/create/" method="post" autocomplete="off">
	<tr class="xrow">
		<td class="alt1" style="padding: 15px;">
			Recipients: <br />
			<input type="text" id="recipients" name="recipients" class="input" value="{xmb:var recipients}" size="50" onkeyup="namesuggest('namesuggest', 'recipients')" /> <br />
			<span class="smallfont">Seperate multiple with a comma (,)</span>
			<ul id="namesuggest" class="menu menublank">
				
			</ul>
			
			<hr />
			
			Title: <br />
			<input type="text" size="50" name="subject" class="input" /><br /><br />
			
			Message: <br />
			<textarea id="editor" name="message" style="width: 100%;"></textarea>
		</td>
	</tr>
</table>    
<div class="foot" style="text-align: center;">


<input type="hidden" name="securitytoken" value="{xmb:user securitytoken}" />
<input type="hidden" name="fromuser" value="{xmb:user username}" />
<input type="submit" class="button medium white" style="margin-top: 5px;" value="Post!"/>
</div>
</form>