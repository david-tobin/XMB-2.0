<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<div class="head">Member List</div>
	
	<table class="xtable">
			<tr class="xrow">
				<td class="xhead">Username</td>
				<td class="xhead">Status</td>
				<td class="xhead">Email</td>
				<td class="xhead">Private Message</td>
				<td class="xhead">Location</td>
				<td class="xhead">Joined</td>
			</tr>			
			
			{xmb:html memberlist}
			
	</table>

<div class="foot"></div>

{xmb:html footer}
