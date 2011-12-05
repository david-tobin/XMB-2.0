<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<div class="head">Search Results <span class="smallfont" style="float: right;"><?php echo $results;?> Results (<?php echo $search['totalfound'];?> Found) Returned In <?php echo $search['timetaken'];?> Seconds.</span></div>
<table class="xtable">

	<tr class="xrow">
		<td class="xhead" style="text-align: center;"></td>
	</tr>
	
	{xmb:if "$postresults"}
		{xmb:foreach postresults AS key => search}
			<tr class="xrow">
				<td class="searchresults" style="padding-left: 30px;">
				<a href="thread/view/{xmb:var search.tid}/">{xmb:var search.subject}</a>
				<span style="float: right;">{xmb:var search.date}</span><br />
				<hr />
				{xmb:var search.message}... 
				<span class="smallfont" style="float: right; vertical-align: bottom; font-size: 12px;"><a href="thread/view/{xmb:var search.tid}/#message_{xmb:var search.pid}">Read More..</a></span>
				</td>
			</tr>
		{/xmb:foreach}
	{/xmb:if}

	{xmb:if "$userresults"}
		{xmb:foreach userresults AS uid => member}
			<tr class="xrow">
				<td class="searchresults" style="padding-left: 30px;">
				<a href="members/profile/{xmb:var member.username}/">{xmb:var member.username}</a>
				<span style="float: right;">Joined: {xmb:var member.regdate}</span><br />
				<hr />
				{xmb:var bio}
				<span class="smallfont" style="float: right; vertical-align: bottom; font-size: 12px;"><a href="members/profile/{xmb:var member.username}/">View Profile</a></span>
				</td>
			</tr>
		{/xmb:foreach}
	{/xmb:if}

</table>

<div class="foot"></div>

{xmb:html footer}