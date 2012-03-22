<?php
require_once 'core/module.php';
require_once 'core/usermanager.php';

class adminModule extends Module
{
	public function load_page()
	{
		if (!UserManager::is_admin()) {
			header('Location: ?');
		}
		$params = SiteManager::get_querystring_params();
		if (array_key_exists('m', $params)) {
			switch ($params['m']) {
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
			switch ($params['m']) {
				default:
					require_once 'admin_template.php';
				break;
			}
		} else {
			require_once 'admin_template.php';
		}
	}
	
	public function load_widget()
	{
		
	}
	
	public function header_menu_text()
	{
		if (UserManager::is_admin()) {
			return 'admin';
		} else {
			return '';
		}
	}
}
?>