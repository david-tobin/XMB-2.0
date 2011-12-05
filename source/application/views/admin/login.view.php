<?php if (!defined('IN_CODE')) die ('You do not have permission to view this file.') ?>
{xmb:html header}

<form action="admin/processlogin/" method="post">
<div class="login">
	<div class="login-head">Login <span style="float: right; font-size: 11px;">XMB Administration Panel</span></div>
	
	<div class="login-body">
		Username: <input type="text" id="username" placeholder="Username" onload="this.focus();" class="login-input" name="username" /> <br /> <br />
		Password: <input type="password" class="login-input" placeholder="Password" name="password" /> <br /> <br />

		<input type="submit" class="login-input" value="Login" />
	</div>
</div>
</form>

<?php if (!defined('NO_HEAD')) { ?>
	{xmb:html footer}
<?php } ?>