<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<tr class="xrow" style="border-bottom: 1px solid #D1D1D1;">
	<td class="forum" style="padding-left: 15px; vertical-align: top; width: 65%;">
		<span id="col1forum"><a href="forum/display/{xmb:var forum.fid}/">{xmb:var forum.name}</a></span>
		<br />
		<span class="smallfont">
			{xmb:var forum.description}<br />
			&bull; {xmb:phrase sub_forums}: <span id="subforums">{xmb:html forum.subforums}</span>	
		</span>
	</td>
	
	<td class="forum" style="vertical-align: middle; text-align: center;">
		<span class="smallfont">{xmb:var forum.threads}</span>
	</td>
	
	<td class="forum" style="vertical-align: middle; text-align: center;">
		<span class="smallfont">{xmb:var forum.posts}</span>
	</td>
	
	<td class="forum" style="vertical-align: middle; text-align: justify;">
		<span class="smallfont">
			
			<a href="thread/view/{xmb:var forum.seolink}/">{xmb:var forum.lastposttitle}</a>
			<br />{xmb:var forum.lastposttime} By <a href="members/profile/{xmb:var forum.lastposter}/">{xmb:var forum.lastposter}</a>
		</span>
	</td>
</tr>

