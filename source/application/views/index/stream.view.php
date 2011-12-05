<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

{xmb:html header}

<script type="text/javascript"
		src="application/javascript/xmb/xmb_stream.js"></script>
		
<script type="text/javascript">
	$(document).ready(function() {
		$("#streamgo").click(function() {
			var status = $("#streaminput").attr('value');
			$("#statusinput").attr('value', 'Sending...');
			status_post(status);
		});
		
		$("#streaminput").click(function() {
			$(this).attr('value', '');
		});

		$("#streamrefresh").click(function() {
			refreshStream();
		});
	});
</script>

<div class="head">Live Stream <span class="smallfont" style="float: right;"><a style="cursor: pointer;" id="streamrefresh">Refresh</a> | <a href="index/home/">View Forum List</a></span></div>
<div id="streamholder">
<table class="xtable">
    <tr class="xrow">
        <td class="xhead" colspan="2">
            <input id="streaminput" size="100" type="text" class="input" style="color: gray; padding: 3px; font-size: 10px;" value="Post a new status update!" />
            <button id="streamgo" class="button">Post</button>
        </td>
    </tr>
    
    {xmb:html streams}
    
</table>
</div>
<div class="foot"></div>

<br />

<div class="head">{xmb:phrase statistics}</div>
<table class="xtable">
	<tr class="xrow">
		<td class="xhead">{xmb:phrase whos_online}</td>
		<td class="xhead" style="text-align: right">{xmb:phrase forum_info}</td>
	</tr>

	<tr class="xrow">

		<td class="stats" style="width: 60%;">{xmb:html onlineusers} <br />
		<span class="smallfont"> There are currently {xmb:var onlinecount} members and
{xmb:var guestcount} guests viewing the forum. </span></td>

		<td class="stats" style="padding-right: 7px; text-align: right;">{xmb:phrase threads}: {xmb:var stats.threadcount} |
		{xmb:phrase posts}: {xmb:var stats.postcount} | {xmb:phrase users}: {xmb:var stats.usercount} <br />
		{xmb:phrase welcome_newest} <a
			href="members/profile/{xmb:var stats.newest_usernamelink}">{xmb:var stats.newest_username}</a>
		</td>
	</tr>
</table>
<div class="foot"></div>

{xmb:html footer}
