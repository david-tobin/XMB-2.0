<?php if (! defined ( 'IN_CODE' )) die ( 'You cannot run this file directly.' ); ?>

{xmb:html header}

<script type="text/javascript"
	src="application/javascript/xmb/forum_display.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("img[rel]").overlay();
    });
</script>

<div style="text-align: left;">
	<a href="{xmb:option site_url}/thread/newthread/{xmb:var forums.forumid}/" class="button medium white">New Thread</a>
	<span style="float: right;">{xmb:html pagination}</span>
</div>

<div class="spacer"></div>

<div class="head">{xmb:var forum.name}</div>

<table class="xtable shadow">
	<tr class="xrow" style="text-align: left;">
		<td class="xhead"></td>
		<td class="xhead" style="text-align: right;">Replies</td>
		<td class="xhead" style="text-align: right;">Views</td>
		<td class="xhead" style="text-align: right;">Last Post</td>
	</tr>
				{xmb:html threads}
		</table>

<div class="foot"></div>
<br style="line-height: 8px;" />
<div style="float: right;">{xmb:html pagination}</div>
<br />

<div class="simple_overlay" id="overlay">
        <div><input type="button" class="input" id="delete_{xmb:var forums.tid}" /></div>
</div>

{xmb:html footer}			