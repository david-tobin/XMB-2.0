<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<tr>
    <td class="stream" style="width: 2%; vertical-align: top; text-align: center;">
        <img src="{xmb:url avatar}" height="50" width="50" alt="{xmb:var stream.streamer}'s Avatar" />
    </td>
        
    <td class="stream hover" style="vertical-align: top;">    
        <a href="members/profile/{xmb:var stream.streamer}/">{xmb:var stream.streamer}</a>
        <br />
        <span style="color: gray;">
            {xmb:var params.status} <br />
        <span class="time smallfont">{xmb:var params.dateline}</span>
        </span>
    </td>
</tr>