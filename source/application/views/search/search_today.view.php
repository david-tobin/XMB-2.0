<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<div class="head">Today's Posts</div>
<table class="xtable">

	<tr class="xrow">
		<td class="xhead" style="text-align: center;"></td>
		<td class="xhead" style="text-align: center;">Thread</td>
		<td class="xhead" style="text-align: center;">Author</td>
		<td class="xhead" style="text-align: center;">Last Post</td>
	</tr>

	{xmb:html threads}
	
</table>

<div class="foot"></div>

{xmb:html footer}