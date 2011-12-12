<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<ul class="memberpopup menublank" id="mempop_<?php echo $thread['pid']?>">
	<li><?php echo $thread['author'];?><img src="avatar/get/{xmb:var thread.uid}" alt="<?php echo $thread['username'];?>'s Avatar" width="100" style="float: right;" /></li>
          	<li></li>
            <li><span class="smallfont">
            <?php echo $thread['usertitle'];?>
            </span> </li>
            <li>Posts:
            <?php echo $thread['totalposts'];?>
            </li>
            <li>Registered:
            <?php echo $thread['regdate'];?>
            </li>
            <li>Location:
            <?php echo $thread['location'];?>
            </li>
            <li>Status:
            <?php echo $thread['mstatus'];?>
            </li>
</ul>