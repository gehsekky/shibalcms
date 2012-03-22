<?php
require_once 'datamanager.php';
require_once 'sessionmanager.php';

class UserManager
{
	public static function login($username, $password)
	{
		$sql = sprintf('select * from user where username = \'%s\' and password = sha1(concat(\'%s\', \'%s\', dynamic_salt))', 
				DataManager::sanitize($username), DataManager::sanitize($GLOBALS['site_settings']['site_static_salt']), 
				DataManager::sanitize($password));
		$result = DataManager::query($sql);
		if ($result) {
			$num_rows = DataManager::num_rows($result);
			if ($num_rows > 0) {
				// login
				$row = DataManager::fetch_array($result);
				$_SESSION['user_loggedin_id'] = $row['user_id'];
				$_SESSION['user_loggedin_isadmin'] = $row['is_admin'];
			} else {
				// nothing returned
				return false;
			}
		} else {
			// error
			return false;
		}
		return true;
	}
	
	public static function current_user_id()
	{
		return isset($_SESSION['user_loggedin_id']) ? $_SESSION['user_loggedin_id'] : null;
	}
	
	public static function logout()
	{
		SessionManager::stop();
	}
	
	public static function logged_in() {
		return isset($_SESSION['user_loggedin_id']) && $_SESSION['user_loggedin_id'] !== '';
	}
	
	public static function is_admin()
	{
		return isset($_SESSION['user_loggedin_isadmin']) && $_SESSION['user_loggedin_isadmin'] === 	'1';
	}
}
?>