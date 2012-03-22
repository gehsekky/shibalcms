<?php
require_once 'sessionmanager.php';
require_once 'modulemanager.php';


class SiteManager
{
	public static function init()
	{
		SessionManager::start();
	}

	public static function load_page()
	{
		// set up template
		require_once 'styles/template.php';
	}
	
	public static function load_header_menu()
	{
		$module_names = ModuleManager::get_modules('header_menu_display_order');
		foreach ($module_names as $module_name) {
			$module = ModuleManager::load_module($module_name);
			if ($module->header_menu_text() != '') {
				echo '<li><a href="' . ($module->header_menu_href() === '' ? '?module=' . $module_name : $module->header_menu_href()) . '">' . $module->header_menu_text() . '</a></li>' . "\n";
			}
		}
	}
	
	public static function load_widgets()
	{
		$module_names = ModuleManager::get_modules('widget_display_order');
		foreach ($module_names as $module_name) {
			$module = ModuleManager::load_module($module_name);
			$module->load_widget();
		}
	}
	
	public static function load_content()
	{
		$module = ModuleManager::get_current_module();
		$module->load_content();
	}
	
	public static function run()
	{
		if (!isset($GLOBALS['site_settings'])) {
			SiteManager::init();
		}
		
		$module = ModuleManager::get_current_module();
		if ($module) {
			$module->load_page();
		}
	}
	
	public static function get_querystring_params()
	{
		try {
			$qs_pairs = explode('&', $_SERVER['QUERY_STRING']);
			$qs_params = array();
			foreach ($qs_pairs as $qs_pair) {
				$pair = explode('=', $qs_pair);
				if (count($pair) > 1) {
					$qs_params[$pair[0]] = $pair[1];
				}
			}
			return $qs_params;			
		} catch (Exception $e) {
			echo 'ERROR: invalid querystring.';
		}
	}
}
?>