<?php if (!defined('IN_CODE')) die ('You do not have permission to view this file.'); ?>

{xmb:html header}

<table class="xtable" style="border: none;">
	<tr>
		<td class="alt1">
			<h3>XMB Administration Control Panel</h3>
			<hr />
				<form action="admin/settings/update/" method="post">
					<table class="infobox">
						{xmb:foreach settings AS key => setting}
							<tr>
								<td class="alt1" style="width: 50%; vertical-align: top;" title="{xmb:var setting.varname}">
									{xmb:var setting.title} <br />
									<span class="smallfont">{xmb:html setting.description}</span>
								</td>
								<td class="alt1">
									{xmb:html setting.input}
								</td>
							</tr>
						{/xmb:foreach}
						
						<tr>
							<td class="alt1" style="text-align: center;" colspan="2">
								<input type="submit" value="Save" class="gobutton" />
							</td>
					</table>
				</form>	
		</td>
	</tr>
</table>
		
{xmb:html footer}