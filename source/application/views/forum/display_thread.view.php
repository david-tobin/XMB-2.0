<? if (! defined ( 'IN_CODE' )) die ( 'You cannot run this file directly.' ); ?>
<tr class="xrow" style="text-align: left;"
	id="thread_{xmb:var forums.tid}">

	<td class="threadbit" id="area_{xmb:var forums.tid}"
		ondblclick="edit_title({xmb:var forums.tid})"
		style="width: 60%; border-bottom: 1px solid #e1e1e1; padding-left: 10px;"">
	<span id="subject_{xmb:var forums.tid}"><a
		href="thread/view/{xmb:var forums.tid}/">{xmb:var forums.subject}</a></span>
					
					<?php
					if ($this->registry->perm->is_mod () == 1) {
						?>
				    <span style="float: right;">
                        <img src="http://forums.xmbforum.com/images/admin/deletetopic.gif" rel="overlay" />
                    </span>
					<?php
					}
					?>
					 <br />
	<span class="smallfont" style="padding-left: 5px;"><a
		href="members/profile/{xmb:var forums.author}/">{xmb:var forums.author}</a></span>
	</td>

	<td class="threadbit"
		style="text-align: center; border-bottom: 1px solid #e1e1e1; padding-left: 10px;"">
	<span style="font: 14px Arial;">{xmb:var forums.replies}</span></td>

	<td class="threadbit"
		style="text-align: center; border-bottom: 1px solid #e1e1e1; padding-left: 10px;"">
	<span style="font: 14px Arial;">{xmb:var forums.views}</span></td>

	<td class="threadbit"
		style="text-align: right; border-bottom: 1px solid #e1e1e1; padding-left: 10px;"">
	<span class="lastpost"><a
		href="thread/view/{xmb:var forums.lastpostid}/">{xmb:var forums.lastposttitle}</a>
	<br />
	<span class="time">{xmb:var forums.lastposttime}</span> by <a
		href="members/profile/{xmb:var forums.lastpostby}/">{xmb:var forums.lastpostby}</a></span>
	</td>
</tr>