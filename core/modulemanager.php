<?php
class ModuleManager
{
	public static function load_module($module_name) {
		$module = false;
		// check if module file exists
		$module_path = $GLOBALS['site_settings']['site_path'] . 'modules/' . $module_name . '/' . $module_name . '.php';
		if (file_exists($module_path)) {			
			// include it and instantiate
			require_once 'modules/' . $module_name . '/' . $module_name . '.php';		
			$module_class_name = $module_name . 'Module';
			$module = new $module_class_name();
		}
		return $module;
	}
	
	public static function get_modules($display_order) {
		$modules_path = $GLOBALS['site_settings']['site_path'] . 'modules/';
		$module_names = ModuleManager::get_dirs($modules_path);
		$sorty = array();
		foreach ($module_names as $module_name) {
			$module = ModuleManager::load_module($module_name);
			switch ($display_order) {
				case 'widget_display_order':
					$sorty[$module_name] = $module->widget_display_order();
					break;
				case 'header_menu_display_order':
					$sorty[$module_name] = $module->header_menu_display_order();
					break;
				default:
					$sorty[$module_name] = $module->header_menu_display_order();
			}
		}
		asort($sorty);
		$sorted_names = array();
		$sorty_length = count($sorty);
		foreach ($sorty as $module_name => $dummy_display_order) {
			$sorted_names[] = $module_name;
		}
		return $sorted_names;
	}
	
	public static function get_current_module() {
		$params = SiteManager::get_querystring_params();
		$module_name = array_key_exists('module', $params) ? $params['module'] : '';
		if ($module_name == '') {
			$module_name = $GLOBALS['site_settings']['site_default_module'];
		}
		return ModuleManager::load_module($module_name);
	}
	
	// extract directories from scandir results
	public static function get_dirs($path, $files_too = false, $extra_filters = array())
	{
		if (substr($path, strlen($path) - 1) != '/') {
			$path = $path . '/';
		}
		$scandir = scandir($path);
		$dirs = array();
		foreach ($scandir as $dir) {
			// skip dirs that start with '.'
			if (($files_too || is_dir($path . $dir)) &&
			(strpos($dir, '.') === false || strpos($dir, '.') !== 0)) {
				array_push($dirs, $dir);
			}
		}
		return array_diff($dirs, $extra_filters);
	}
}
?>