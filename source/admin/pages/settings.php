<?
$settinggroups = $admin->loadsettinggroups ();

if (empty ( $_REQUEST ['settings'] ) && $_REQUEST ['update'] == 0)
	$_REQUEST ['settings'] = 'main';
if ($_REQUEST ['update'] == 1)
	$_REQUEST ['settings'] = 'update';

include ('./functions/forms.functions.php');
?>

<div class="xtable">
<div class="xrowgroup">
<div class="xrow">
<div class="sidebar">
<div class="sidebar-title">Settings</div>
				<?
				foreach ( $settinggroups as $key => $value ) {
					?>
				<div class="sidebar-inset"><a
	href="index.php?do=settings&amp;settings=<?=$key?>"><?=$value?></a></div>
				<?
				}
				?>
			</div>

<div class="content">
<form action="index.php?do=settings&amp;update=1" method="post">
<div class="xtable">
<div class="xrowgroup">
							<?
							if ($_REQUEST ['settings'] == 'main') {
								?>
								Please Select A Setting Group From The Side Panel.
							<?
							} else if (empty ( $_REQUEST ['update'] ) && ! empty ( $_REQUEST ['settings'] )) {
								$admin->cleaner->clean ( 'r', 'settings', 'TYPE_STR' );
								$settings = $admin->cleaner->settings_db;
								?><input type="hidden" name="settingname" value="<?=$settings?>" /> <?
								$settings = $admin->loadsettings_group ( $settings );
								
								while ( $setting = $admin->db->fetch_array ( $settings ) ) {
									?>
											<div class="xrow">
<div class="alt1" style="width: 50%; vertical-align: top;" title="$this->registry->options['<?=$setting['varname']?>']">
													<?=$setting ['title']?> <br />
<span class="smallfont"><?=$setting ['description']?></span></div>
<div class="alt1"><?
									$setting ['datatype'] ( $setting ['varname'], $setting ['value'] );
									?></div>
</div>
								<?
								}
								?>
				<?
							}
							?>
				
				<?
				if ($_REQUEST ['settings'] == 'update') {
					$admin->cleaner->clean ( 'p', 'settings', 'TYPE_ARRAY' );
					$admin->cleaner->clean ( 'p', 'settingname', 'TYPE_STR' );
					
					$settings = $admin->cleaner->settings;
					$settingname = $admin->cleaner->settingname_db;
					
					$update = 'SET ';
					$skip = 1;
					foreach ( $settings as $key => $value ) {
						if ($skip == 2) {
							$key = str_replace ( '_db', '', $key );
							$skip = 0;
							
							$admin->db->query ( "
							UPDATE " . X_PREFIX . "settings
							SET value = '".$value."'
							WHERE varname = '".$key."'
						" );
						}
						$skip ++;
					}
					$settings = serialize($admin->loadsettings());
					
					$admin->cache->rebuild_cache('options', $settings);
					$admin->redirect('./index.php?do=settings&settings='.$settingname);
				}
				
				?>
					</div>
</div>
				<?
				if ($_REQUEST ['settings'] != 'main') {
					?><div class="foot"
	style="text-align: center;"><input type="submit" class="login-input"
	value="Save" /></div><?
				}
				?>
			

</div>
</form>
</div>
</div>
</div>