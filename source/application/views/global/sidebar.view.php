<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<script type="text/javascript" src="application/javascript/xmb/sidebar.js"></script>

<table class="xtable">
		<tr class="round-top sidebar-row" id="start">
			<td class="xcat" id="start">
				{xmb:user username} <?php if ($user['uid'] > 0) ?><span class="smallfont"><a id="sidebar-button">[Hide]</a></span><?php ;?>
			</td>
		</tr>	
		
		<tr class="sidebar-row">
			<td class="alt2 smallfont">
				Last Visit: {xmb:user lastvisit}
			</td>
		</tr>
		
		<tr class="sidebar-row">
			<td class="alt2 smallfont">
				Messages: <a href="messages/inbox/"><?php if ($user['unreadpm'] == 0) {?> {xmb:user pmtotal} <?php } else { ?> <span style="color: red;">You have {xmb:user unreadpm} new message(s)</span> <?php } ?></a>
			</td>
		</tr>
		
		<tr class="sidebar-row">
			<td class="alt2 smallfont">
				Status: <span id="sidebar_status">{xmb:user status}</span>
			</td>
		</tr>
		
		<tr class="sidebar-row" id="start">
			<td class="xcat" id="start">
				Latest Forum Posts
			</td>
		</tr>
		
		{xmb:html latestposts}
		
		<tr class="sidebar-row" id="start">
			<td class="xcat" id="start">
				Who's Online? <span class="smallfont">({xmb:var onlinecount} users &amp; {xmb:var guestcount} guests)</span>
			</td>
		</tr>
		
		<tr class="sidebar-row">
			<td class="alt2 smallfont">
				{xmb:html onlineusers}
			</td>
		</tr>
</table>