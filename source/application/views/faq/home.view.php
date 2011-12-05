{xmb:html header}

<div class="head">FAQ's</div>

<table class="xtable">
	<tr class="xrow">
		<td class="xhead"></td>
	</tr>
	
	<?php
	foreach ( $faqs as $key => $value ) {
		?>
		<tr class="xrow">
		<td class="alt1"><a href="faq/view/<?php echo $value['faqid'];?>/"><?php echo $value['title'];?></a>
		<hr />
		<span class="smallfont"><?php echo substr($value['contents'], 0, 150 )?>...</span>
		</td>
	</tr>
	<?php
	}
	?>	
</table>

<div class="foot"></div>

{xmb:html footer}