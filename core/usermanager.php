<?php
require_once 'sitesettings.php';
require_once 'datamanager.php';
require_once 'sessionmanager.php';

class UserManager
{
	/**
	 * login user by checking credentials and setting session vars.
	 * 
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public static function login($username, $password)
	{
		$sql = sprintf('select * from user where username = \'%s\' and password = sha1(concat(\'%s\', \'%s\', dynamic_salt))', 
				DataManager::sanitize($username), DataManager::sanitize(SiteSettings::get('site_static_salt')), 
				DataManager::sanitize($password));
		$result = DataManager::query($sql);
		if ($result) {
			$num_rows = DataManager::num_rows($result);
			if ($num_rows > 0) {
				// login
				$row = DataManager::fetch_array($result);
				$_SESSION['user_loggedin_id'] = $row['user_id'];
				$_SESSION['user_loggedin_username'] = $row['username'];
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
	
	/**
	 * get current user id
	 * 
	 * @return int
	 */
	public static function current_user_id()
	{
		return isset($_SESSION['user_loggedin_id']) ? $_SESSION['user_loggedin_id'] : null;
	}
	
	/**
	 * get current user name
	 * 
	 * @return string
	 */
	public static function current_username()
	{
		return isset($_SESSION['user_loggedin_username']) ? $_SESSION['user_loggedin_username'] : null;
	}
	
	/**
	 * logout a user
	 */
	public static function logout()
	{
		SessionManager::stop();
	}
	
	/**
	 * find out if user is logged in
	 * 
	 * @return bool
	 */
	public static function logged_in() {
		return isset($_SESSION['user_loggedin_id']) && $_SESSION['user_loggedin_id'] != '';
	}
	
	/**
	 * find out if user is admin
	 * 
	 * @return bool
	 */
	public static function is_admin()
	{
		return isset($_SESSION['user_loggedin_isadmin']) && $_SESSION['user_loggedin_isadmin'] == '1';
	}
	
	public static function add_user($username, $password, $is_admin)
	{
		
	}
}
?>