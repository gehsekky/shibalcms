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
				case 'process_settings':
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
			switch ($params['m']) {
				case 'manage_settings':
					require_once 'admin_template_manage_settings.php';
					break;
				case 'manage_modules':
					require_once 'admin_template_manage_modules.php';
					break;
				default:
					require_once 'admin_template.php';
			}
		} else {
			require_once 'admin_template.php';
		}
	}
	
	public function header_menu_text_dynamic()
	{
		if (UserManager::is_admin())
		{
			return 'admin';
		}
		else
		{
			return '';
		}
	}
}
?>