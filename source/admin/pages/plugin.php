<?php

$admin->cleaner->clean('g', 'action', TYPE_STR);

$action = $admin->cleaner->action_db;

if (empty($action)) {
	$action = 'main';
}

if ($action == 'main') {

	$installed = $admin->db->query("
		SELECT * FROM ".X_PREFIX."packages
	");
}
?>
<div class="xtable">
	<div class="xrowgroup">
		<div class="xrow">
			<div class="sidebar">
				<div class="sidebar-title">Packages</div>
					<div class="sidebar-inset"><a href="index.php?do=plugins">Home</a></div>
					<div class="sidebar-inset"><a href="index.php?do=plugins&action=install">Install</a></div>
					<div class="sidebar-inset"><a href="index.php?do=plugins&action=download">Download</a></div>
					<div class="sidebar-inset"><a href="index.php?do=plugins&action=plugins">Plugins</a></div>
			</div>

			<div class="content">
			<? if ($action == 'main') { ?>
				<div class="head">Installed Packages</div>
				<div class="xtable">
					<div class="xrowgroup">
						<div class="xrow">
							<div class="xhead" style="width: 40%;">Package</div>
							<div class="xhead">Status</div>
							<div class="xhead">Installed</div>
							<div class="xhead">Controls</div>
						</div>
	
<?	while ($package = $admin->db->fetch_array($installed)) { 
	$package['packageactive'] = ($package['packageactive'] == 1) ? '<span style="color:green;">&#10004;</span>' : '<span style="color:red;">&#10008;</span>';
	$package['packageinstalled'] = $admin->xmbtime('F jS Y', $package['packageinstalled']);
	?>
		<div class="xrow">
			<div class="alt1" style="width: 40%;"><?=$package['packagename']?></div>
			<div class="alt1"><?=$package['packageactive']?></div>
			<div class="alt1"><?=$package['packageinstalled']?></div>
			<div class="alt1">
			<? if ($package['packageid'] != 1) {?><a href="index.php?do=plugins&action=packageedit&packageid=<?=$package['packageid']?>">[Edit]</a> <a href="index.php?do=plugins&action=packageremove&packageid=<?=$package['packageid']?>">[Remove]</a><?}
			else {?>-<?;}?>
			</div>
		</div>
<?	}
?>

				</div>
			</div>	
<?	
} else if ($action == 'install') {
	
?>	
			<form action="index.php?do=plugins&action=doinstall" method="post" enctype="multipart/form-data">
			<div class="head">Install a Package</div>
				<div class="xtable">
					<div class="xrowgroup">
						<div class="xrow">
							<div class="alt1">Upload Package File</div>
							<div class="alt1"><input type="file" name="package" /></div>
						</div>
					</div>
				</div>
			<div class="foot" style="text-align: center;"><input type="submit" class="login-input" value="Install" /></div>		
			</form>	
<?	
} else if ($action == 'doinstall') {
	$admin->cleaner->clean('f', 'package', 'TYPE_ARRAY');

	$package = $admin->cleaner->package;
	
	require_once ('../application/classes/xml.class.php');
	
	$package = new XMB_XML($admin, $package['tmp_name']);

	$admin->db->query("
		INSERT INTO ".X_PREFIX."packages (packageid, packagename, packageinstalled)
		VALUES ('".$package->attr['package']['varname']."', '".$package->attr['package']['name']."', '".X_TIME."')
	");
	
	foreach ($package->value['plugin'] AS $key => $value) {
		$admin->db->query("
			INSERT INTO ".X_PREFIX."plugins (pluginname, plugincode, package, pluginhook, pluginlocation)
			VALUES ('".$package->attr['plugin'][$key]['name']."', '".$value."', '".$package->attr['package']['varname']."', '".$package->attr['plugin'][$key]['hook']."', '".$package->attr['plugin'][$key]['controller']."')
		");
	}
	
	foreach ($package->value['phrase'] AS $key => $value) {
		$admin->db->query("
			INSERT INTO ".X_PREFIX."phrases (varname, value, package, phrasegroup)
			VALUES ('".$package->attr['phrase'][$key]['varname']."', '".$value."', '".$package->attr['package']['varname']."', '".$package->attr['phrase'][$key]['phrasegroup']."')
		");
	}
	
	sleep(3);
?>
	<div class="head">Package Information</div>
				<div class="xtable">
					<div class="xrowgroup">
					
						<div class="xrow">
							<div class="alt1">Package Name</div>
							<div class="alt1"><?=$package->attr['package']['name']?></div>
						</div>
						
						<div class="xrow">
							<div class="alt1">Package Author</div>
							<div class="alt1"><?=$package->attr['package']['author']?></div>
						</div>
						
						<div class="xrow">
							<div class="alt1">Package Version</div>
							<div class="alt1"><?=$package->attr['package']['version']?></div>
						</div>
						
						<div class="xrow">
							<div class="alt1">Package Plugins</div>
							<div class="alt1"><?=count($package->attr['plugin'])?></div>
						</div>
						
						<div class="xrow">
							<div class="alt1">Package Phrases</div>
							<div class="alt1"><?=count($package->attr['phrase'])?></div>
						</div>
						
						<div class="xrow">
							<div class="alt1">Installed Successfully</div>
							<div class="alt1">&#10004;</div>
						</div>
					</div>
				</div>
			<div class="foot" style="text-align: center;"><input type="button" class="login-input" onclick="window.location='index.php?do=plugins';" value="Complete" /></div>		
<?
} else if ($action == 'plugins') {
	$packages = $admin->db->query("
		SELECT * FROM ".X_PREFIX."packages
		ORDER BY packageinstalled ASC
	"); 
	
	$plugins = $admin->db->query("
		SELECT * FROM ".X_PREFIX."plugins
		ORDER BY pluginid ASC
	"); 
	
	$packagecache = array();
	while ($package = $admin->db->fetch_array($packages)) {
		$packagecache[$package['packageid']]['name'] = $package['packagename'];
		$packagecache[$package['packageid']]['plugins'] = array();
	}
	?>
	<div class="head">Plugins</div>
				<div class="xtable">
					<div class="xrowgroup">
	<?				
	while ($plugin = $admin->db->fetch_array($plugins)) {
		$packagecache[$plugin['package']]['plugins'][] = $plugin;	
	}
	$i = 0;
	foreach ($packagecache AS $key => $value) {
?>
<div class="xrow">
	<div class="xhead" style="font-weight: bold;"><?=$value['name']?></div>
	<div class="xhead"></div>
	<div class="xhead"></div>
	<div class="xhead"></div>
	<div class="xhead"></div>
</div>

<div class="xrow">
	<div class="xhead">Plugin Name</div>
	<div class="xhead">Active</div>
	<div class="xhead">Hook</div>
	<div class="xhead">Controller</div>
	<div class="xhead" style="text-align: right; padding-right: 25px;">Controls</div>
						</div>

<? foreach ($packagecache[$key]['plugins'] AS $plugkey => $plugininfo) { ?>

<div class="xrow">	
	<div class="alt1"><?=$plugininfo['pluginname']?></div>
	<div class="alt1"><? echo ($plugininfo['pluginactive'] == 1) ? '&#10004;' : '&#10008;'; ?></div>
	<div class="alt1"><?=$plugininfo['pluginhook']?></div>	
	<div class="alt1"><?=$plugininfo['pluginlocation']?></div>
	<div class="alt1" style="text-align: right;"><? if ($plugininfo['package'] != 'xmb') { ?><a href="index.php?do=plugins&action=editplugin&pluginid=<?=$plugininfo['pluginid']?>">[Edit]</a> <a href="index.php?do=plugins&action=removeplugin&pluginid=<?=$plugininfo['pluginid']?>">[Remove]</a><? } ?></div>
</div>	
<? $i++; ?>		
<? } ?>						
<? } ?>
					</div>
				</div>
			<div class="foot" style="text-align: center;"></div>		
<?							
}
?>

</div>

</div>
	</div>
		</div>