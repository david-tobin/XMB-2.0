<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
{xmb:html header}

<form action="login/index/" method="post">
<div class="head">Login</div>

<table class="xtable shadow">
	
	<tr class="xrow">
		<td class="xhead" colspan="2"><span style="color: red;">{xmb:var fail}</span></td>
	</tr>	
	
	<tr class="xrow">
		<td class="alt1">Username:</td>
		<td class="alt1"><input type="text" name="xmbusername"
			id="xmbusername" class="input" /></td>
	</tr>

	<tr class="xrow">
		<td class="alt1">Password:</td>
		<td class="alt1"><input type="password" name="xmbpassword"
			id="xmbpassword" class="input" /></td>
	</tr>

	<tr class="xrow">
		<td class="alt1">Remember Me? <input type="checkbox"
			name="rememberme" /></td>
		<td class="alt1"><input type="submit" class="submit" value="Login" /></td>
	</tr>
	
</table>
<div class="foot">
</form>
<span class="smallfont"> To take full advantage of our site, you will need to 
<a href="register/">Register</a>. </span>
</div>

{xmb:html footer}