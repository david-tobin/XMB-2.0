<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr class="xrow">
	<td class="xhead">Subject:&nbsp; {xmb:var message.subject}</td>
</tr>


<tr class="xrow">
	<td class="xhead" style="width: 5%;">From:&nbsp; <a href="{xmb:url members/profile/{xmb:var message.fromuser}}">{xmb:var message.fromuser}</a></td>
</tr>

<tr class="xrow">
	<td class="alt1">
		{xmb:var message.message}
	</td>
</tr>

<tr class="xrow">
	<td class="alt1">
		<span class="smallfont" style="float: right;">Reply - Delete</span>
	</td>
</tr>

</table>

<div class="foot"></div>