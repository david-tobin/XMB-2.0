<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<script type="text/javascript"
	src="application/javascript/tiny_mce/jquery.tinymce.js"></script>	

<script type="text/javascript">
$().ready(function() {
	$('#editor').tinymce({
		
		script_url : '{xmb:option site_url}/application/javascript/tiny_mce/tiny_mce.js',

		// General options
		theme : "advanced",
		plugins : "bbcode,safari,emotions,iespell,inlinepopups,media,print,contextmenu,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,tabfocus",

		setup : function(ed) {
			 <?php foreach ($bbcode_buttons AS $key => $value) { ?>
		        ed.addButton('<?php echo $key;?>', {
		            title : '<?php echo ucfirst($key);?>',
		            image : '<?php echo $value['image'];?>',
		            onclick : function() {
						ed.focus();
						ed.selection.setContent('[<?php echo $value['tag'];?><?php if ($value['params'] == 1) {?>=<?php }?>]'+ed.selection.getContent({format : 'text'})+'[/<?php $value['tag']?>]');
		            }
		        });
		    <?php } ?>
		    },
		
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,fontsizeselect,link,unlink,image,<?php echo $buttons;?>",
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
</script>

<form action="thread/doreply/{xmb:var tid}/" method="post" enctype="multipart/form-data" id="wysiwyg">

<div class="head">New Reply</div>
 
<table class="xtable">
		<tr class="xrow">
			<td class="xhead">Required*</td>
			<td class="xhead"></td>
		</tr>
			
			<tr class="xrow">
				<td class="alt1" style="text-align: center;">
					Subject: 
				</td>
				<td class="alt1">
					<input type="text" class="input" name="subject" size="50" /><br /><br />
				</td>
			</tr>
			
			<tr class="xrow">
				<td class="alt1" style="text-align: center; width: 15%; vertical-align: top;">
					Message: <br /><br />
					<span class="smallfont">BBCode is on. <br />
					Smilies are on. </span>
				</td>
				<td class="alt1" style="text-align: center;">
					<textarea id="editor" name="message" class="tinymce" style="width: 75%;"></textarea>
				</td>
			</tr>
						
			<tr class="xrow">
				<td class="xhead">Attachments</td>
				<td class="xhead"></td>
			</tr>
			
			<tr class="xrow">
			<td class="alt1" style="text-align: center;">
				<input type="file" name="attachment[]" id="attach" class="attach" />
			</td>
			<td class="alt1">
				<div id="attaches">
					<input type="button" class="clean" value="+" onclick="newfile();" />
				</div>	
			</td>
		</tr>	
</table>

<div class="foot" style="text-align: center;">
	<input type="hidden" value="<?php echo $user['securitytoken'];?>" name="securitytoken" />
	<input type="submit" class="submit" value="Post!" />
</div>

</form>

{xmb:html footer}