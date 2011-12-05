<?
$settinggroups = $admin->loadsettinggroups ();

foreach ($_REQUEST AS $key => $value) {
	$admin->cleaner->clean('r', $key, 'TYPE_STR');
}

$action = $admin->cleaner->action_db;
$user = $admin->cleaner->user_db;

if (empty ( $action )) $action = 'main';

?>

<div class="xtable">
	<div class="xrowgroup">
		<div class="xrow">
			<div class="sidebar">
				<div class="sidebar-title">Members</div>
					<div class="sidebar-inset"><a href="index.php?do=members&action=edit">Edit User</a></div>
					<div class="sidebar-inset"><a href="index.php?do=members&action=ranks">User Ranks</a></div>
					<div class="sidebar-inset"><a href="index.php?do=members&action=ban">Banning</a></div>
			</div>

			<div class="content">
			<? 
				if ($action == 'main') { ?>
					Please select an option from the side.
			<?	} else if ($action == 'edit' && empty($user)) { ?>
					<form action="index.php?do=edit" method="get">
						<div class="head">Edit User</div>
							<div class="xtable">
								<div class="xrowgroup">
									<div class="xrow">
										<div class="alt1">Username </div>
										<div class="alt1">
											<input type="hidden" name="do" value="members" />
											<input type="hidden" name="action" value="edit" />
											<input type="text" name="user" class="login-input" />
										</div>
									</div>
								</div>
							</div>
						<div class="foot" style="text-align: center;"><input type="submit" value="Search" class="input-login" /></div>
					</form>	
			<?	} else if ($action == 'edit' && !empty($user)) { 
				$user = strtolower($admin->cleaner->user_db);
				
				$userinfo = $admin->db->query_one("
					SELECT * FROM ".X_PREFIX."members
					WHERE LOWER(username) = '".$user."'
				");
				
				$usergroups = $admin->db->query("
					SELECT * FROM ".X_PREFIX."usergroups
				");
				$ugoptions = '';
				while ($ugroup = $admin->db->fetch_array($usergroups)) {
					if ($ugroup['usergroupid'] == $userinfo['usergroupid']) {
						$ugoptions .= '<option value="'.$ugroup['usergroupid'].'" selected="1">'.$ugroup['name'].'</option>';
					} else {
						$ugoptions .= '<option value="'.$ugroup['usergroupid'].'">'.$ugroup['name'].'</option>';
					}	
				}
			?>
				<form action="index.php?do=members&action=userupdate" method="post">
					<div class="head"><?=$userinfo['username']?></div>
						<div class="xtable">
							<div class="xrowgroup">
								<div class="xrow">
									<div class="alt1">Username</div>
									<div class="alt1"><?=$userinfo['username']?></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Usertitle</div>
									<div class="alt1"><input type="text" name="usertitle" value="<?=$userinfo['usertitle']?>" class="login-input" /></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Posts</div>
									<div class="alt1"><input type="text" name="posts" value="<?=$userinfo['posts']?>" class="login-input" /></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Email Address</div>
									<div class="alt1"><input type="text" name="email" value="<?=$userinfo['email']?>" class="login-input" /></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Status</div>
									<div class="alt1"><input type="text" name="status" value="<?=$userinfo['status']?>" class="login-input" /></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Location</div>
									<div class="alt1"><input type="text" name="location" value="<?=$userinfo['location']?>" class="login-input" /></div>
								</div>
								
								<div class="xrow">
									<div class="alt1">Usergroup</div>
									<div class="alt1">
										<select name="usergroup">
											<?=$ugoptions?>
										</select>
									</div>
								</div>
							</div>
						</div>
					<div class="foot" style="text-align: center;"><input type="submit" value="Save" class="input-login" /></div>
				</form>
			<? } else if ($action == 'userupdate') {
				print_r($_POST);
			}
			?>
			
			
			</div>

</div>
	</div>
		</div>