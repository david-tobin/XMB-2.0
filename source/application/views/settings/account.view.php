<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

<table class="xtable">
    <tr class="xrow">
        <td class="xhead" id="account" colspan="2">Account</td>
    </tr>
    
	<tr class="xrow">
			<td class="alt1 bold small">Username</td>
			<td class="alt1 small">{xmb:user username}</td>
	</tr>
    
    <tr class="xrow">
			<td class="alt1 bold small">Email Address</td>
			<td class="alt1 small">{xmb:user email}</td>
	</tr>
    
    <tr class="xrow">
			<td class="alt1 bold small">Password</td>
			<td class="alt1 small">********</td>
	</tr>
    
    <tr class="xrow" id="privacy">
        <td class="xhead" colspan="2">Privacy</td>
    </tr>
    
    <tr class="xrow">
			<td class="alt1 bold small">Allow Private Messages</td>
			<td class="alt1 small">
                <input type="checkbox" name="pms" id="pms" checked="checked" style="float: left;" />
            </td>
	</tr>
    
    <tr class="xrow">
        <td class="xhead" colspan="2">Linked Accounts</td>
    </tr>
    
    <tr class="xrow">
			<td class="alt1 bold small">Link Facebook</td>
			<td class="alt1 small">Facebook</td>
	</tr>
    
    <tr class="xrow">
			<td class="alt1 bold small">Link Twitter</td>
			<td class="alt1 small">Twitter</td>
	</tr>
</table>	
				
<div class="foot"></div>

<div class="spacer"></div>
<input class="button medium green" type="submit" name="save" value="Save" />
