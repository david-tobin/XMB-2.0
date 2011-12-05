<?php if (! defined ( 'IN_CODE' )) die ( 'You cannot run this file directly.' );?>
<script type="text/javascript">
$(function() {
	$("#triggers a").overlay({  
	    target: '#gallery', 
	    expose: '#f1f1f1' 
	}).gallery({ 
	    speed: 800 
	});
});
</script>

<div class="head">My Attachments</div>

<div id="triggers">
	<table class="xtable">
		{xmb:html myattachments}
	</table>
</div>

<div class="simple_overlay" id="gallery"><a class="prev">prev</a> <a
	class="next">next</a>


<div class="info"></div>


<img class="progress"
	src="http://static.flowplayer.org/tools/img/overlay/loading.gif" /></div>

<div class="foot"></div>
<br />
&nbsp;
<span style="float: right;"><input class="button" id="upload"
	type="button" value="Upload New"
	onclick="window.location='{xmb:option site_url}/attachment/upload/'" /></span>
