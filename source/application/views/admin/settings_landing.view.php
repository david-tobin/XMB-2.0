<?php if (!defined('IN_CODE')) die ('You do not have permission to view this file.'); ?>

{xmb:html header}

<table class="xtable" style="border: none;">
	<tr>
		<td class="alt1">
			<h3>XMB Administration Control Panel</h3>
			<hr />
			
			<table class="infobox" style="text-align: left;">
				<caption style="text-align: left;">Setting Groups</caption>	
				{xmb:foreach setting_groups AS key => settings}
					<tr>
						<td class="alt1 alt1_hover">
							<a href="admin/settings/view/?setting_group={xmb:var settings.varname}">{xmb:var settings.groupname}</a>
							<br />
							<span class="smallfont">{xmb:var settings.description}</span>
						</td>
					</tr>
				{/xmb:foreach}
			</table>
			
		</td>
	</tr>	
</table>

{xmb:html footer}