<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<div class="head">Search Results <span class="smallfont" style="float: right;">{xmb:var results} Results ({xmb:var search.totalfound} Found) Returned In {xmb:var timetaken} Seconds.</span></div>
<table class="xtable">

	<tr class="xrow">
		<td class="xhead" style="text-align: center;"></td>
	</tr>
    
    {xmb:html content}

</table>

<div class="foot"></div>

{xmb:html footer}