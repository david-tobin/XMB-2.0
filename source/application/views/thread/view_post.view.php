<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr class="xrow">
  <td class="alt1">
  
  <table class="basetable" style="width: 100%;">
  
      <tr class="xrow">
        <td class="xhead" style="text-align: center;" colspan="2"><span style="float: left"> <img src="images/bookmarksite_digg.gif" title="Digg This!" /> <img src="images/bookmarksite_delicious.gif" title="Submit This To del.icio.us" /> <img src="images/bookmarksite_stumbleupon.gif" title="StumbleUpon This!" /> <img src="images/bookmarksite_google.gif" title="Submit This To Google!" /> </span></td>  
      </tr>
      
      <tr class="xrow">
        <td class="alt1" style="margin-left: 3px;">
        	<span class="postheader">
	            <?php echo $thread['postsubject'];?>
	            <span class="smallfont" style="float: right;">Posted:
	            <?php echo $thread['posted'];?>
	            </span> <br />
	            <a href="members/profile/<?php echo $thread['author'];?>/" id="username_<?php echo $thread['pid'];?>" style="text-decoration: none; cursor: pointer;" onmouseover="menu2('username_<?php echo $thread['pid'];?>', 'mempop_<?php echo $thread['pid'];?>');">
	            <?php echo $thread['username'];?>
	            </a> <sup><span class="smallfont" style="color: <?php echo $thread['online_color'];?>; font-weight: bold;">
	            <?php echo $thread['online'];?>
	            </span></sup> <br />
          </span>
           <hr />
          <div id="message_<?php echo $thread['pid'];?>">
            <?php echo $thread['message'];?>
          </div>
          <br />
          <br />
          
         	<?php  if (isset($attachments[$thread['pid']]) && !empty($attachments[$thread['pid']])) { ?>
         			<div id="triggers">
         				<fieldset>
         					<legend>Attachments</legend>
         			<?php  foreach ($attachments[$thread['pid']]['aid'] AS $id => $attachment) { ?>
         				<a href="attachment/image/<?php echo $attachment;?>/" title="<?php echo $attachments[$thread['pid']]['title'][$id];?>"><img width="100" src="attachment/image/<?php echo $attachment;?>/" /></a>
         		<?php  } ?>
         				</fieldset> 
         			</div>
         		<?php  } ?>
          <br />
          <br />
          <hr style="width: 200px; float: left;" />
          <br />
          <?php echo $thread['sig'];?>
          </td>
      </tr>
      <tr class="xrow">
        <td class="alt1" colspan="2"><?php  if ($this->registry->options['thread_ratings'] == 1) { ?>
          <span class="ratings" style="background-color: <?php echo $thread['rating_color'];?>; color: white;" id="ratings_<?php echo $thread['pid'];?>"> <span id="ratingcolor_<?php echo $thread['pid'];?>">
          <?php echo $thread['rating_prefix'];?><span id="rating_<?php echo $thread['pid'];?>"><?php echo $thread['rating'];?></span> </span> </span>
          <?php  if (!in_array($this->registry->user['username'], $thread['raters'])) { ?>
          <span style="float: right; color: green;" id="ratebuttons-good_<?php echo $thread['pid'];?>">
          <input type="button" class="postrate" id="postrateg_<?php echo $thread['pid'];?>" onclick="post_rate(<?php echo $thread['pid'];?>, 1);" value="&#10004;" />
          </span>
          <span style="float: right; color: red;">
          <input type="button" class="postrate" id="postrateb_<?php echo $thread['pid'];?>" onclick="post_rate(<?php echo $thread['pid'];?>, 0);" value="&#10008;" />
          </span>
           <?php  } ?>
          <?php  } ?>
          </td>
      </tr>
    </table>
   		<div class="foot round" style="text-align: right;"> <span class="buttons-dark"> <a id="editbutton_<?php echo $thread['pid'];?>" onclick="quickedit(<?php echo $thread['pid'];?>)">Edit</a> <a href="thread/reply/<?php echo $thread['tid'];?>/">Reply</a> <a href="thread/">Quote</a> </span> </div>
    </td>
</tr>
