<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<?php if ($messages['count'] == 0) { ?>
	<tr class="xrow">
		<td class="xhead"></td>
		<td class="xhead">From</td>
		<td class="xhead">Sent</td>
	</tr>	
<?php } ?>

<tr class="xrow">
	<td class="alt1 hover">
		<a href="{xmb:url messages/view/{xmb:var messages.pmid}}">{xmb:html messages.subject}</a>
		<br /><span class="smallfont">{xmb:html messages.message}</span>
	</td>
	<td class="alt1">{xmb:var messages.fromuser}</td>
	<td class="alt1">{xmb:var messages.sent}</td>
</tr>