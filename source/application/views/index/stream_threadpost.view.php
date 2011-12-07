<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr>
    <td class="stream" style="width: 2%; vertical-align: top; text-align: center;">
        <img src="{xmb:url avatar}" height="50" width="50" alt="{xmb:var stream.streamer}'s Avatar" />
    </td>
        
    <td class="stream hover" style="vertical-align: top;">    
        <a href="members/profile/{xmb:var stream.streamer}/">{xmb:var stream.streamer}</a>
        <br />
        <span class="smallfont">
        has just created a new thread: <a href="{xmb:url thread/view/{xmb:var params.tid}}" style="font-size: 16px; color: gray;">{xmb:var params.subject}</a> <br />
        <span class="time smallfont">{xmb:var params.dateline}</span>
        </span>
    </td>
</tr>