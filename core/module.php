<?php
require_once 'datamanager.php';

abstract class Module
{
	private $_row;
	
	function __construct($row)
	{
		$this->_row = $row;
	}
	
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
	
	final public function widget_display_order()
	{
		return $this->_row['widget_display_order'];
	}
	
	final public function header_menu_text()
	{
		if ($this->_row['header_menu_display_text'] == '' || $this->_row['header_menu_display_text'] == null)
		{
			return $this->header_menu_text_dynamic();
		}
		else
		{
			return $this->_row['header_menu_display_text'];
		}
	}
	
	public function header_menu_text_dynamic()
	{
		return '';
	}
	
	final public function header_menu_href()
	{
		return $this->_row['header_menu_href'];
	}
	
	final public function header_menu_display_order()
	{		
		return $this->_row['header_menu_display_order'];
	}
}
?>