<?php if (!defined('IN_CODE')) die ('You do not have permission to view this file.'); ?>

<div class="leftbox" id="home">
	<ul>
		<li id="title">Home</li>
		<li><a href="admin/home/">Main Page</a></li>
		<li><a href="admin/credits/">Credits</a></li>
		<li><a href="admin/about/">About XMB</a></li>
	</ul>	
</div>

<div class="leftbox" id="settings">
	<ul>
		<li id="title">Settings</li>
		<li><a href="admin/settings/landing/">Edit Settings</a></li>
		<li><a href="admin/settings/search_module/">Search Mode</a></li>
	</ul>	
</div>

<div class="leftbox" id="cache">
	<ul>
		<li id="title">Cache</li>
		<li><a href="admin/cache/clear/">Clear Cache</a></li>
		<li><a href="admin/cache/rebuild/">Rebuild Cache</a></li>
		<li><a href="admin/cache/view/">Moniter Cache</a></li>
	</ul>
</div>

<div class="leftbox" id="members">
	<ul>
		<li id="title">Members</li>
		<li><a href="admin/members/search/">Search Members</a></li>
		<li><a href="admin/members/add/">Add a Member</a></li>
		<li><a href="admin/members/ban/">Ban a Member</a></li>
	</ul>
</div>

<div class="leftbox" id="forums">
	<ul>
		<li id="title">Forums</li>
		<li><a href="admin/forums/edit/">Edit Forums</a></li>
		<li><a href="admin/forums/add/">Add a Forum</a></li>
		<li><a href="admin/forums/permissions/">Edit Permissions</a></li>
	</ul>
</div>

<div class="leftbox" id="usergroups">
	<ul>
		<li id="title">Usergroups</li>
		<li><a href="admin/usergroups/manage/">Usergroup Manager</a></li>
		<li><a href="admin/usergroups/add/">Add a Usergroup</a></li>
	</ul>
</div>

<div class="leftbox" id="bbcode">
	<ul>
		<li id="title">BBCode</li>
		<li><a href="admin/bbcode/manage/">Manage BBCode</a></li>
		<li><a href="admin/bbcode/add/">Add New BBCode</a></li>
	</ul>
</div>

<div class="leftbox" id="tasks">
	<ul>
		<li id="title">Tasks</li>
		<li><a href="admin/tasks/manage/">Task Manager</a></li>
		<li><a href="admin/tasks/add/">Add A New Task</a></li>
	</ul>
</div>

<div class="leftbox" id="logs">
	<ul>
		<li id="title">Logs</li>
		<li><a href="admin/logs/moderation/">Moderator Logs</a></li>
		<li><a href="admin/logs/administration/">Administrator Logs</a></li>
		<li><a href="admin/logs/login/">Failed Login Logs</a></li>
		<li><a href="admin/logs/task/">Task Logs</a></li>
	</ul>
</div>

<script type="text/javascript">
	if (typeof(document.getElementById(current)) != 'undefined') {
		$('#' + current).removeClass('boxnotselected');
		$('#' + cureent).addClass('boxselected');
	} else {
		$('#home').removeClass('boxnotselected');
		$('#home').addClass('boxselected');
</script>