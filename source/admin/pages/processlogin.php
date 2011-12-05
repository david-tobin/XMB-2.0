<?php
DEFINE('NO_HEAD', true);

die(print_r($_POST));

$admin->cleaner->clean('p', 'username', 'TYPE_STR');
$admin->cleaner->clean('p', 'password', 'TYPE_STR');

$username = $admin->cleaner->username_db;
$password = $admin->cleaner->password_db;

include('../application/datahandlers/login.datahandle.php');

$login = new XMB_Datahandler_Login($admin);

$login->set_info('username', $username);
$login->set_info('password', $password);
$login->set_info('remember', 0);
$login->set_info('type', 'admin');

$login->build();


$admin->redirect('./index.php?do=main');

?>