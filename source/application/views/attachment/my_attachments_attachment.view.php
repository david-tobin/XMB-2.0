<?php if (! defined ( 'IN_CODE' )) die ( 'You cannot run this file directly.' );?>
<tr class="xrow">
	<td class="attachment" title="{xmb:var attach.filename}"><span
		style="float: right;"> <input type="checkbox"
		name="attachment_{xmb:var attach.aid}" /> </span> <span
		style="float: left;" id="thumbs"> <a title="{xmb:var attach.filename}"
		href="attachment/image/{xmb:var attach.aid}/"><img
		src="attachment/image/{xmb:var attach.aid}/" width="100px"
		alt="{xmb:var attach.filename}" /></a> </span>
		 
		 &nbsp; {xmb:var attach.shortname} <br />
	&nbsp; <span class="smallfont" style="color: gray;">
					Uploaded: {xmb:var attach.uploaded} <br />
					&nbsp;&nbsp;&nbsp; Size: {xmb:var attach.filesize}kb
			</span> <br />
	</td>
</tr>
