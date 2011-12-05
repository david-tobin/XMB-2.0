<?php  if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<div class="head">User Control Panel</div>
	
<table class="xtable">
		<tr class="xrow">
		
			<td class="alt1" style="width: 20%; vertical-align: top;">
				<table class="xtable">
						<tr class="xrow">
							<td class="profilebox" style="width: 20%; vertical-align: top;">
								<div class="usercp-cat">Settings</div>
								<div class="usercp-link"><a href="#">Profile</a></div>
								<div class="usercp-link"><a href="#">Site</a></div>									<div class="usercp-link"><a href="#">Privacy</a></div>
								<div class="usercp-link"><a href="#">Private Messages</a></div>
								<div class="usercp-link" style="border-bottom: 1px solid #D0D0D0;"><a href="#">Password &amp; Email</a></div>
								
								<div class="usercp-cat">Misc</div>
								<div class="usercp-link"><a href="#">Subscriptions</a></div>
								<div class="usercp-link"><a href="attachment/myattachments/">Attachments</a></div>
								<div class="usercp-link"><a href="#">Avatar</a></div>
								<div class="usercp-link" style="border-bottom: 1px solid #D0D0D0;"><a href="#">Friend/Ignore List</a></div>
							</td>
						</tr>
				</table>
			</td>					
					
			<td class="alt1" style="vertical-align: top;">
				{xmb:html content}
			</td>
					
	</tr>
</table>	

<div class="foot"></div>

{xmb:html footer}