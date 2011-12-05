<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<link type="text/css" href="pages/css/admin.css"
	rel="stylesheet" />

<title>XMB Administration Panel</title>	
</head>

<body>
<? if ($admin->nocontainer == false) { ?>	

<table class="container" style="background: none;">
	<tr class="xrow">
		<td style="background-color: #5f5f5f;">			
			<img src="../images/logo.png" alt="AdminCP" />
		</td>
	</tr>
	<tr class="xrow">
		<td class="alt1">	
<table class="xtable">
	<tr class="xrow">
		<td class="alt1">
			<div class="links">
				<a href="index.php?do=settings">Forum Settings</a>
				<a href="index.php?do=lookfeel">Look & Feel</a>
				<a href="index.php?do=language">Language</a>
				<a href="index.php?do=forums">Forum Management</a>
				<a href="index.php?do=members">Members</a>
				<a href="index.php?do=usergroups">Usergroups</a>
				<a href="index.php?do=plugins">Plugins</a>
				<a href="index.php?do=database">Database</a>
				<a href="index.php?do=logs">Logs</a>
				<a href="index.php?do=misc">Misc</a>
			</div>
		</td>
	</tr>
</table>			

<? } ?>	