<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<tr class="xrow">
	<td class="forum">
        <span style="margin-right: 30px;">
            <img src="{xmb:url avatar/get/{xmb:var search.uid}}" alt="{xmb:var search.username}'s Avatar" height="60" width="60" style="float: left; margin-right: 10px;" />
        </span>
    	
        <span class="bold medium"><a href="thread/view/{xmb:var search.tid}/">{xmb:html search.subject}</a></span>
    	
        <span style="float: right;">{xmb:var search.date}</span><br />
    	
        <hr />
    	
        {xmb:html search.message}... 
    	
        <span class="smallfont" style="float: right; vertical-align: bottom; font-size: 12px;">
            <a href="thread/view/{xmb:var search.tid}/#message_{xmb:var search.pid}">Read More..</a>
        </span>
	</td>
</tr>