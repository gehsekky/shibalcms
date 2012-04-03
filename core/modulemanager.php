<?php
require_once 'sitesettings.php';
require_once 'datamanager.php';

class ModuleManager
{
	/**
	 * loads module into object and returns it. checks to see if module is 
	 * installed. if not, installs it.
	 * 
	 * @param string $module_name
	 * @return Module
	 */
	public static function load_module($module_name)
	{
		$module = null;
		
		// check if module is installed. if not, install.
		// TODO check if enabled
		if (!ModuleManager::is_installed($module_name)) {
			ModuleManager::install_module($module_name);
		}
		
		$sql = sprintf('select * from module where name = \'%s\'', DataManager::sanitize($module_name));
		$result = DataManager::query($sql);
		if ($result) {
			$row = DataManager::fetch_array($result);
			
			// check if module file exists
			$module_path = SiteSettings::get('site_path') . 'modules/' . $module_name . '/' . $module_name . '.php';
			if (file_exists($module_path)) {
				// include it and instantiate
				require_once 'modules/' . $module_name . '/' . $module_name . '.php';
				$module_class_name = $module_name . 'Module';
				$module = new $module_class_name($row);
			}
		}
		return $module;
	}
	
	/**
	 * check if module is installed
	 * 
	 * @param string $module_name
	 * @return bool
	 */
	public static function is_installed($module_name)
	{
		$sql = sprintf('select count(*) as modulecount from module where name = \'%s\'', DataManager::sanitize($module_name));
		$result = DataManager::query($sql);
		if ($result) {
			$row = DataManager::fetch_array($result);
			
			if ($row['modulecount'] != 0) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * store module in database
	 * 
	 * @param unknown_type $module_name
	 */
	public static function install_module($module_name)
	{
		$sql = sprintf(	'insert into module ' . 
						'(name, header_menu_display_order, header_menu_display_text, ' . 
						'header_menu_href, widget_display_order) ' . 
						'values (\'%s\', -1, null, null, -1)', 
						DataManager::sanitize($module_name));
		DataManager::query($sql);
	}
	
	/**
	 * returns array of module names for display purposes
	 * 
	 * @param string $display_order
	 * @return array
	 */
	public static function get_modules($display_order)
	{
		$sorted_names = array();
		$sql = "select name from module where $display_order > -1 order by $display_order asc";
		$result = DataManager::query($sql);
		if ($result) {
			$row = DataManager::fetch_array($result);
			while ($row) {
				$sorted_names[] = $row['name'];
				$row = DataManager::fetch_array($result);
			}
		}

		return $sorted_names;
	}
	
	/**
	 * get current module (from querystring or setting)
	 * 
	 * @return Module
	 */
	public static function get_current_module()
	{
		$params = SiteManager::get_querystring_params();
		$module_name = array_key_exists('module', $params) ? $params['module'] : '';
		if ($module_name == '') {
			$module_name = SiteSettings::get('site_default_module');
		}
		return ModuleManager::load_module($module_name);
	}
	
	/**
	 * extract directories from scandir results
	 * 
	 * @param string $path
	 * @param bool $files_too
	 * @param array $extra_filters
	 * @return array
	 */
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