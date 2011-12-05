<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<tr class="xrow">	
	<td class="alt1" style="width: 45%; padding-left: 10px;">
		<a href="{xmb:url messages/viewmessage/{xmb:var messages.pmid}}">{xmb:var messages.subject}</a>
	</td>
	
	<td class="alt1">
		<a href="{xmb:url members/profile/{xmb:var messages.fromuser}}">{xmb:var messages.fromuser}</a>
	</td>
	
	<td class="alt1">
		{xmb:var messages.sent}
	</td>
</tr>	

</table>

<div class="foot"></div>