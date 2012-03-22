<?php
abstract class Module
{
	public function load_page()
	{
		SiteManager::load_page();
	}
	
	public function load_content()
	{
		
	}
	
	public function load_widget()
	{
		
	}
	
	public function widget_display_order()
	{
		return 999;
	}
	
	public function header_menu_text()
	{
		return '';
	}
	
	public function header_menu_href()
	{
		return '';
	}
	
	public function header_menu_display_order()
	{
		return 999;
	}
}
?>