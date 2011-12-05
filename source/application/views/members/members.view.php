<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<script type="text/javascript">
$(function() { 
    $("ul.tabs").tabs("div.panes > div", {effect: 'fade', fadeOutSpeed: 300}); 
});
</script>


<div class="head">{xmb:var members.username} <span style="font: 12px Tahoma;">{xmb:var members.status}</span></div>
	
<table class="xtable">
    <tr class="xrow">
        <td class="xhead" colspan="2"></td>
    </tr>
    
	<tr class="xrow">
	<td class="alt1" style="width: 25%;">
		<table class="xtable">
				<tr class="xrow">
				
					<td class="profilebox" id="start">
						{xmb:var members.username}
						<span class="smallfont" style="float: right"><a href="{xmb:url messages/send/{xmb:var members.username}}">Send Message</a></span>
					
						<div class="profileinset">
								Join Date:
								<span style="float: right;">{xmb:var members.regdate}</span><br />
								User Title:
								<span style="float: right;">{xmb:var members.usertitle}</span> <br />
								Location:
								<span style="float: right;">{xmb:var members.location}</span><br />
								Status:
								<span style="float: right;">{xmb:var members.status}</span> <br />
						</div>
					</td>
				</tr>	
					
				<tr class="xrow">	
					<td class="profilebox">
						<span class="smallfont">Avatar</span>
						<div class="profileinset" style="text-align: center;">
								<img src="{xmb:var members.avatar}" alt="{xmb:var members.username}'s Avatar" width="100" />
						</div>
					</td>	
				</tr>
				
				<tr class="xrow">	
					<td class="profilebox">
						<span class="smallfont">Last Post - {xmb:var members.subject}</span>
						<div class="profileinset">
							{xmb:var members.message}
						</div>
						 <span class="smallfont" style="float: right;"><a href="thread/view/{xmb:var members.lastthreadid}/#message_{xmb:var members.lastpostid}">Read More...</a></span>
					</td>	
				</tr>
				
			</table>							
	</td>
	
	<td class="alt1" style="vertical-align: top;">
		<ul class="tabs panebox">
			<li><a href="#messages">Visitor Messages</a></li>
			<li><a href="#updates">Recent Updates</a></li>
			<li><a href="#about">Biography</a></li>
			<li><a href="#contact">Contact Me</a></li>
		</ul>

		<div class="panes">
			<div class="pane">
				<table class="xtable">
					<tr class="xrow">
						<td class="alt1 panebox">
							<span class="smallfont"><a href="#">Test</a> <span style="float: right;">Posted: Today @ 10pm</span></span>
							<br />
							Just testing visitor messages!
							<hr />
						</td>
					</tr>
				</table>
			</div>
			
			<div class="pane">
				<table class="xtable">
					<tr class="xrow">
						<td class="alt1 panebox">
							<a href="#">Admin</a> posted a reply in thread <a href="#">Test Thread</a> at <span class="time">10:04 PM</span>
						</td>
					</tr>
					
					<tr class="xrow">
						<td class="alt1 panebox">
							<a href="#">Admin</a> created thread <a href="#">Test Thread</a> at <span class="time">10:02 PM</span>
						</td>
					</tr>	
					
					<tr class="xrow">
						<td class="alt1 panebox">
							<a href="#">Admin</a> posted a visitor message to <a href="#">Test</a> at <span class="time">09:59 PM</span>
						</td>
					</tr>	
					
					<tr class="xrow">	
						<td class="alt1 panebox">
							<a href="#">Admin</a> changed status to <em>Test Status Update!</em> at <span class="time">09:56 PM</span>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="pane">
				<table class="xtable">
					<tr class="xrow">
						<td class="alt1 panebox">
							{xmb:var members.bio}
						</td>
					</tr>
				</table>		
			</div>
			
			<div class="pane">
				<table class="xtable">
					<tr class="xrow">
						<td class="alt1 panebox">
							Website: <a href="{xmb:user site}">{xmb:user site}</a>
							<br /><br />
							Aim: {xmb:var members.aim}<br />
							MSN: {xmb:var members.msn}<br />
							ICQ: {xmb:var members.icq}<br />
							Yahoo: {xmb:var members.yahoo}<br />
							Youtube: {xmb:var members.youtube}<br />
							Twitter: {xmb:var members.twitter}<br />
						</td>
					</tr>
				</table>
			</div>
		</div>
		

	</td>

	</tr>
</table>

{xmb:html footer}