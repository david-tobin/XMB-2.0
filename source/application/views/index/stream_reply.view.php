<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr>
    <td class="stream" style="width: 7%; vertical-align: top;"><img src="images/default_avatar.gif" height="70" alt="{xmb:var stream.streamer}'s Avatar" /></td>
        
    <td class="stream" style="vertical-align: top;">    
        <a href="members/profile/{xmb:var stream.streamer}/">{xmb:var stream.streamer}</a>
        <br />
        <span class="smallfont">
        has just replied to the thread: <a href="{xmb:url thread/view/{xmb:var params.tid}}" style="font-size: 16px; color: gray;">{xmb:var params.subject}</a> <br />
        <span style="background: #D0D0D0; padding: 1px; margin: 5px;">{xmb:var params.message}</span> <br />
        <span class="time smallfont">{xmb:var params.dateline}</span>
        </span>
    </td>
</tr>