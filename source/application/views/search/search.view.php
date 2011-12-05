<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

{xmb:html header}

<div class="head">Search</div>
<table class="xtable">

	<tr class="xrow">
		<td class="alt1" style="text-align: right; width: 20%;">Find:</td> 
		<td class="alt1"><input type="text" class="input" name="query" size="50" /></td>
	</tr>
	
	<tr class="xrow">	
		<td class="alt1" style="text-align: right; width: 20%;">Sort By:</td> 
		
		<td class="alt1">
			<select name="sortby" class="input">
				<option value="1">Date Posted</option>
				<option value="2">Relevance</option>
			</select>
		</td>
	</tr>
	
	<tr class="xrow">
		<td class="alt1" style="text-align: right; width: 20%;">Content Type:</td> 
		<td class="alt1">
			<select name="content" class="input">
				<option value="-1">All</option>
				<option value="1">Posts</option>
				<option value="2">Members</option>
			</select>
		</td>
	</tr>
</table>
<div class="foot"></div>
<input type="submit" class="button white medium" value="Search" />


{xmb:html footer}