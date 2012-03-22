<?php
require_once 'core/module.php';
require_once 'core/sitemanager.php';
require_once 'core/usermanager.php';

class userModule extends Module
{
	public function load_page()
	{
		if (!UserManager::is_admin()) {
			header('Location: ?');
		}
		$params = SiteManager::get_querystring_params();
		if (array_key_exists('m', $params)) {
			switch($params['m']) {
				case 'process_user':
					if (isset($_POST['user_addnew_editor_user_id']) && $_POST['user_addnew_editor_user_id'] !== '') {
						$userid = $_POST['user_addnew_editor_user_id'];
						$username = isset($_POST['user_addnew_username']) && $_POST['user_addnew_username'] !== '' ? $_POST['user_addnew_username'] : '';
						$password = isset($_POST['user_addnew_password']) && $_POST['user_addnew_password'] !== '' ? $_POST['user_addnew_password'] : '';
						$is_admin = isset($_POST['user_addnew_isadmin']) && $_POST['user_addnew_isadmin'] === 'on' ? 1 : 0;
						$dyamic_salt = mt_rand();
						$remote_ip = $_SERVER['REMOTE_ADDR'];
						
						if ($userid !== '') {
							// update user
							if ($userid !== '0') {								
								if ($password === '') {									
									$sql = sprintf('update user set username = \'%s\', ' . 
													'last_seen_ip = \'%s\', ' .
													'is_admin = %d ' .  
													'where user_id = %d', 
											DataManager::sanitize($username), DataManager::sanitize($remote_ip), 
											DataManager::sanitize($is_admin), DataManager::sanitize($userid));
								} else {									
									$sql = sprintf('update user set username = \'%s\', ' . 
													'password = sha1(concat(\'%s\', \'%s\', dynamic_salt)), ' . 
													'last_seen_ip = \'%s\', ' . 
													'is_admin = %d ' .
													'where user_id = %d', 
											DataManager::sanitize($username), DataManager::sanitize($GLOBALS['site_settings']['site_static_salt']), 
											DataManager::sanitize($password), DataManager::sanitize($remote_ip), 
											DataManager::sanitize($is_admin), DataManager::sanitize($userid));
								}
							} else {
								// add user
								if ($password !== '') {
									$sql = sprintf('insert into user ' . 
													'(username, password, created_on, dynamic_salt, last_seen_ip, last_login, is_admin) ' . 
													'values ' . 
													'(\'%s\', sha1(concat(\'%s\', \'%s\', \'%s\')), now(), \'%s\', \'%s\', now(), %d)', 
											DataManager::sanitize($username), DataManager::sanitize($GLOBALS['site_settings']['site_static_salt']), 
											DataManager::sanitize($password), DataManager::sanitize($dynamic_salt), DataManager::sanitize($dynamic_salt), 
											DataManager::sanitize($remote_ip), DataManager::sanitize($is_admin));
								} else {
									header('Location: ?module=user&m=error');
								}
							}
							
							if (!DataManager::query($sql)) {
								header('Location: ?module=user&m=error');
							}
						}
					}
					header('Location: ?module=user&m=manage');
					break;
				default:
					SiteManager::load_page();
					break;
			}
		} else {
			SiteManager::load_page();
		}
	}
	
	public function load_content()
	{
		$params = SiteManager::get_querystring_params();
		if (array_key_exists('m', $params)) {
			switch($params['m']) {
				case 'manage':
					require_once 'modules/user/user_template_manage.php';
					break;
				case 'error':
					echo 'there was an error processing your request.';
					break;
				default:
					break;
			}
		} else {
			
		}		
	}
	
	public function header_menu_text()
	{
		return '';
	}
}
?>