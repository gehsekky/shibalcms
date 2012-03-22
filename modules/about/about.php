<?php
require_once 'core/module.php';
require_once 'core/sitemanager.php';

class aboutModule extends Module
{
	public function load_content()
	{
		require_once 'modules/about/about_template.php';
	}
	
	public function header_menu_text()
	{
		return '';
	}
}
?>