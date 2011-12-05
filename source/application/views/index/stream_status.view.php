<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr>
    <td class="stream" style="width: 7%; vertical-align: top;"><img src="images/default_avatar.gif" height="70" alt="{xmb:var stream.streamer}'s Avatar" /></td>
        
    <td class="stream" style="vertical-align: top;">    
        <a href="members/profile/{xmb:var stream.streamer}/">{xmb:var stream.streamer}</a>
        <br />
        <span style="color: gray;">
            {xmb:var params.status} <br />
        <span class="time smallfont">{xmb:var params.dateline}</span>
        </span>
    </td>
</tr>