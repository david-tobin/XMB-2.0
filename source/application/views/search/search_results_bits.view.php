<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<tr class="xrow">
	<td class="searchresults" style="padding-left: 30px;">
	<a href="thread/view/<?php echo $search['tid'];?>/"><?php echo $search['subject'];?></a>
	<span style="float: right;"><?php echo $search['date'];?></span><br />
	<hr />
	<?php echo $search['message'];?>... 
	<span class="smallfont" style="float: right; vertical-align: bottom; font-size: 12px;"><a href="thread/view/<?php echo $search['tid'];?>/#message_<?php echo $search['pid'];?>">Read More..</a></span>
	</td>
</tr>