<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

{xmb:html forumdisplay}

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

<br />

{xmb:html footer}