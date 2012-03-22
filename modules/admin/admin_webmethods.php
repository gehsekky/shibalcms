<?php
require_once '../../config.php';
require_once '../../core/usermanager.php';
require_once '../../core/sessionmanager.php';

SessionManager::start();

if (!UserManager::is_admin())
{
	header('Location: ../../?');
}

if (isset($_REQUEST['method'])) {
	$method = $_REQUEST['method'];
	switch ($method) {
		default:
			header('HTTP/1.1 400 Bad Request');
	}
}
?>