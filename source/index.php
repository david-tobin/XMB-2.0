<?php

/**
 * XMB Index File
 *
 * @Author David "DavidT" Tobin
 * @Package XMB 2.0
 * @Copyright (c) 2001-2009 The XMB Group
 * @Licence GPL 3.0
 * @Version $Id:$
 */

/** Set Error Reporting **/
error_reporting(E_ALL & ~E_NOTICE);

/** Include The Initialization File **/
include ('./init.php');

/** Create Instance Of The Router **/
$xmb->route = new Router($xmb);

/** Set The Router Path **/
$xmb->route->set_path(X_PATH . '/application/controller');

/** Create Instance Of The Templater **/
$xmb->view = new Template($xmb);

if ($xmb->controller != 'admin') {
	/** Set Online Users & Latest Posts **/
	$latestposts = '';
	foreach ($latest as $late) {
		$late['dateline'] = $xmb->xmbtime('h:i', $late['dateline']);

		$xmb->view->setvar('latest', $late);

		$latestposts .= $xmb->view->loadtovar('latest_posts', 'global');
	}

	$xmb->view->setvar('latestposts', $latestposts);
	$xmb->view->setvar('onlineusers', $usersonline);
	$xmb->view->setvar('onlinecount', $onlinecount);
	$xmb->view->setvar('guestcount', $guestcount);
} else {
	$xmb->in_admin = true;
}
/** Turn on zlib compression if neccessary **/
if (ini_get('zlib.output_compression') == 0)
	ini_set('zlib.output_compression', 1);

/** Load The Current Route And Calls On The Specified Action **/
$xmb->route->load();

?>