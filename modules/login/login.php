<?php
require_once 'core/module.php';
require_once 'core/sitemanager.php';
require_once 'core/usermanager.php';

class loginModule extends Module
{
	public function load_page()
	{
		$params = SiteManager::get_querystring_params();
		if (array_key_exists('m', $params)) {
			switch($params['m']) {
				case 'process':
					if (isset($_POST['login_username']) && $_POST['login_username'] !== '' && 
							isset($_POST['login_password']) && $_POST['login_password'] !== '') {
						if (UserManager::login($_POST['login_username'], $_POST['login_password'])) {
							header('Location: ?module=login&m=success');
						} else {
							// login failed
							header('Location: ?module=login&m=error');
						}
					} else {
						// missing argument
						header('Location: ?module=login&m=error');
					}
					break;
				case 'logout':
					UserManager::logout();
					header('Location: ?');
					break;
				case 'success':
					// TODO redirect to referer
					header('Location: ?');
					break;
				default:
					SiteManager::load_page();
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
				case 'error':
					echo 'there was an error processing your request.';
					break;
				default:
					break;
			}
		} else {
			require_once 'modules/login/login_template.php';
		}
	}
	
	public function header_menu_text()
	{
		if (!UserManager::logged_in()) {
			return 'login';
		} else {
			return 'logout';
		}
	}
	
	public function header_menu_href()
	{
		if (!UserManager::logged_in()) {
			return '';
		} else {
			return '?module=login&m=logout';
		}
	}
	
	public function header_menu_display_order()
	{
		return 10;
	}
	
	public function load_widget()
	{
		require_once 'modules/login/login_widget_template.php';
	}
	
	public function widget_display_order()
	{
		return 0;
	}
}
?>