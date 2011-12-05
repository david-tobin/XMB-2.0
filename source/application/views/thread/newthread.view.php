<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<!--<script type="text/javascript"
	src="application/javascript/tiny_mce/jquery.tinymce.js"></script>	

<script type="text/javascript">
$().ready(function() {
	$('#editor').tinymce({
		
		script_url : '{xmb:option siteurl}/application/javascript/tiny_mce/tiny_mce.js',

		// General options
		theme : "advanced",
		plugins : "bbcode,safari,emotions,iespell,inlinepopups,media,print,contextmenu,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,tabfocus",
		
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,fontsizeselect,link,unlink,image",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js"
	});
});
		
		function newfile() {
			var html = '<div class="xrow"> <div class="alt1" style="text-align: center;"><input type="file" name="attach[]" id="attach" class="attach" /></div><div class="alt1"><input type="button" class="clean" value="+" onclick="newfile();" /></div></div>';

			var current = document.getElementById('attaches').innerHTML;

			document.getElementById('attaches').innerHTML = current + html;
		}
</script>
-->

<form action="thread/postthread/" method="post" enctype="multipart/form-data" id="wysiwyg">

<div class="head">New Thread</div>
 
<div class="xtable">
	<div class="xrowgroup">
		<div class="xrow">
			<div class="xhead">Required*</div>
		</div>
			
			<div class="xrow">
				<div class="alt1" style="float: left;">
					<label for="subject" style="vertical-align: middle;">Subject:</label> <input type="text" class="input" name="subject" id="subject" size="50" /><br /><br />
				</div>
			</div>
			
			<div class="xrow">
				<div class="alt1" style="text-align: center;">
					<textarea id="editor" name="message" class="tinymce" style="width: 75%;"></textarea>
				</div>
			</div>
			
			<!--			
			<div class="xrow">
				<div class="xhead">Attachments</div>
				<div class="xhead"></div>
			</div>
			
			 <div id="attaches">
				<div class="xrow">
					<div class="alt1" style="text-align: center;">
						<input type="file" name="attachment[]" id="attach" class="attach" />
					</div>
					<div class="alt1">
					 	<input type="button" class="clean" value="+" onclick="newfile();" />
					</div>
				</div>
			</div>-->	
		</div>	
</div>

<div class="foot"></div>
<div class="spacer"></div>
	<input type="hidden" value="{xmb:var forumid}" name="forumid" />
	<input type="hidden" value="{xmb:user securitytoken}" name="securitytoken" />
	<input type="submit" class="button white medium" value="Create Thread" />

</form>

{xmb:html footer}