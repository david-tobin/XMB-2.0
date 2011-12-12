<?php  if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}
	
<table class="xtable">
		<tr class="xrow">
		
			<td style="width: 20%; vertical-align: top;">
				<table class="xtable">
						<tr class="xrow">
							<td style="width: 20%; vertical-align: top;">
								<div class="usercp-link hover"><a href="{xmb:url settings}#account">Account</a></div>
                                <div class="usercp-link hover"><a href="{xmb:url settings}#profile">Profile</a></div>
								<div class="usercp-link hover"><a href="{xmb:url settings}#privacy">Privacy</a></div>
								<div class="usercp-link hover"><a href="{xmb:url settings}#avatar">Avatar/Signature</a></div>
								<div class="usercp-link hover" style="border-bottom: 1px solid #D0D0D0;"><a href="{xmb:url settings}#friends">Friend/Ignore List</a></div>
							</td>
						</tr>
				</table>
			</td>					
					
			<td style="vertical-align: top;">
				{xmb:html content}
			</td>
					
	</tr>
</table>	

{xmb:html footer}