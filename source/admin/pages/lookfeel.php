<?
$settinggroups = $admin->loadsettinggroups ();

foreach ($_REQUEST AS $key => $value) {
	$admin->cleaner->clean('r', $key, 'TYPE_STR');
}

$action = $admin->cleaner->action_db;

if (empty ( $action )) $action = 'main';

$sidebar = array(
	'<a href="index.php?do=lookfeel&amp;action=editcss">Edit CSS</a>'
);

print_page('Look & Feel', $sidebar);
?>
			<? 
				if ($action == 'main') { ?>
					Please select an option from the side.
			<?	} else if ($action == 'editcss') { 
			
				$gettemplates = $admin->db->query("
					SELECT * FROM " . X_PREFIX."styles
				");	
			?>
						<? print_table('Edit CSS'); ?>
								
						<? print_cat(array('Controller')); ?>
						
						<?	$columns = array(); ?>
							<?	while ($templates = $admin->db->fetch_array($gettemplates)) {	
								$columns[] = '<a href="index.php?do=lookfeel&amp;action=doeditcss&amp;styleid="'.$templates['styleid'].'">'.ucfirst($templates['stylename']).'</a></div>';	
							}
							print_columns($columns);
							
							print_table_end(); ?>	

				
			<? } else if ($action == 'doeditcss') {
				$admin->cleaner->clean('r', 'template', 'TYPE_STR');
				$admin->cleaner->clean('r', 'styleid', 'TYPE_INT');
				
				$template = $admin->cleaner->template_db;
				$styleid = $admin->cleaner->styleid;
				
			if (empty($template)) {	
				$cssquery = $admin->db->query("
					SELECT template FROM " . X_PREFIX."css
					WHERE styleid = '".$styleid."'
					ORDER BY template ASC
				");	
			?>

					<div class="head">Edit Css</div>
						<div class="xtable">
							<div class="xrowgroup">
							
								<div class="xrow">
									<div class="xhead">Edit CSS</div>
								</div>
						<?	while ($css = $admin->db->fetch_array($cssquery)) { ?>	
									<div class="xrow">
										<div class="alt1"><a href="index.php?do=lookfeel&amp;action=doeditcss&amp;template=<?=$css['template']?>&amp;styleid=<?=$styleid?>"><?=$css['template']?></a></div>
									</div>
									
							<?}?>
								</div>
							</div>
						<div class="foot"></div>
						
				<? } else if (!empty($template) && !empty($styleid)) { 
					$cssquery = $admin->db->query("
					SELECT * FROM " . X_PREFIX."css
					WHERE styleid = $styleid && template = '".$template."'
				");	
			?>

					<div class="head">Edit Css</div>
					<form action="index.php?do=lookfeel&amp;action=savecss" method="post">
						<div class="xtable">
							<div class="xrowgroup">
							
								<div class="xrow">
									<div class="xhead">Edit CSS</div>
								</div>
						<?	while ($css = $admin->db->fetch_array($cssquery)) { ?>	
								<div class="xrow">
									<div class="alt1" style="text-align: center;"><textarea name="contents" cols="100" rows="25" class="login-input"><?=$css['contents']?></textarea></div>
								</div>
								
						<?}?>
									</div>
								</div>
								
								<input type="hidden" name="template" value="<?=$template?>" />
								<input type="hidden" name="styleid" value="<?=$styleid?>" />
						<div class="foot" style="text-align: center;"><input type="submit" value="Save"/></div>
					</form>	
				<? } ?>
				<? } else if ($action == 'savecss') { 
						$admin->cleaner->clean('p', 'template', 'TYPE_STR');
						$admin->cleaner->clean('p', 'styleid', 'TYPE_INT');
						$admin->cleaner->clean('p', 'contents', 'TYPE_STR');
						
						$template = $admin->cleaner->template_db;
						$styleid = $admin->cleaner->styleid;
						$contents = $admin->cleaner->contents_db;
						
						if (!empty($template) && !empty($styleid) && !empty($contents)) {
							$admin->db->query("
								UPDATE ".X_PREFIX."css
								SET contents = '".$contents."'
								WHERE template = '".$template."' && styleid = '".$styleid."'
							");
						}
						
						$admin->redirect('index.php?do=lookfeel&action=doeditcss&styleid='.$styleid.'&template='.$template);
				 	} 
				?>
			</div>
		</div>
	</div>
</div>