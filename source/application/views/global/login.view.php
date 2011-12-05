<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>
<div class="logininfo">
<form action="login/index/" method="post">
<input type="text" name="xmbusername" id="xmbusername" class="login" value="<?php echo $phrases['username'];?>..." onfocus="this.value=''" /> 
<input type="password" name="xmbpassword" id="xmbpassword" class="login" value="<?php echo $phrases['password'];?>" onfocus="this.value=''" />

<input type="hidden" name="securitytoken" value="{xmb:user securitytoken}" />
	
<input type="submit" value="Login!" class="login" style="width: auto;" />
<br />
{xmb:phrase rememberme}? <input type="checkbox"
	name="rememberme" class="checkbox" /> 	
</form>
</div>