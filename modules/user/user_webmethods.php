<?php
require_once '../../config.php';
require_once '../../core/datamanager.php';
require_once '../../core/usermanager.php';

if (!UserManager::is_admin())
{
	header('Location: ../../?');
}

if (isset($_REQUEST['method'])) {
	$method = $_REQUEST['method'];
	switch ($method) {
		case 'delete':
			if (isset($_REQUEST['delete_id']) && intval($_REQUEST['delete_id']) > 0) {
				$sql = sprintf('delete from user where user_id = %d', DataManager::sanitize($_REQUEST['delete_id']));
				if (DataManager::query($sql)) {
					header('HTTP/1.1 200 OK');
				} else {
					header('HTTP/1.1 500 Internal Server Error');
				}
			} else {
				header('HTTP/1.1 400 Bad Request');
			}
			break;
		default:
			header('HTTP/1.1 400 Bad Request');
	}
}
?>