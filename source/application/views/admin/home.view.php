<?php if (!defined('IN_CODE')) die ('You do not have permission to view this file.'); ?>

{xmb:html header}

<table class="xtable" style="border: none;">
	<tr>
		<td class="alt1">
			<h3>XMB Administration Control Panel</h3>
			<hr />
			
			<table class="infobox" style="text-align: left;">
				<caption style="text-align: left;">Useful Infomation</caption>
				<tr>
					<td>XMB Version: 0.1 Pre-Alpha</td>
					<td>Memory Usage: <?php echo round(memory_get_usage()/1048576, 2);?>MB </td>
				</tr>	
				<tr>
					<td>XMB Build: 2376 </td>
					<td>PHP Version <?php echo phpversion();?>. </td>
				</tr>
				<tr>	
					<td>XMB Build Date: 11th May 2010 </td>
					<td>Sapi Name: <?php echo php_sapi_name();?> </td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">Latest Version: <span style="font-weight: bold; text-decoration: underline;">0.1 Pre-Alpha</span></td>
				</tr>
			</table>
			
			<br />
			
			<table class="infobox">
				<caption style="text-align: left;">Useful Tools</caption>
				<tr>
					<td>
						<label for="searchuser">Search Member</label>
						<input type="text" id="searchuser" name="username" class="input" />
						<input type="submit" value="Go" class="gobutton" />
						
						<span style="float: right;">
							<label for="searchuser">Search Forum</label>
							<input type="text" id="searchforum" name="forumname" class="input" />
							<input type="submit" value="Go" class="gobutton" />
						</span>
					</td>
				</tr>
			</table>
			
			<br />
			
			<table class="infobox">
				<caption style="text-align: left;">Contributers</caption>
				<tr>
					<td style="font-weight: bold;">Project Manager</td>
					<td>Kuba1</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Lead Developer</td>
					<td>DavidT</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Graphics</td>
					<td>TheRevTastic</td>
				</tr>
			</table>
		</td>
	</tr>	
</table>

{xmb:html footer}