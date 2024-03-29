<?php
require_once 'datamanager.php';

/**
 * abstract module with default functions
 * 
 * @author gehsekky
 */
abstract class Module
{
	// database row
	private $_row;
	public $name;
	
	/**
	 * constructor
	 * 
	 * @param object $row
	 */
	function __construct($row)
	{
		$this->_row = $row;
		$this->name = $row['name'];
	}
	
	/**
	 * load module page (pre-render)
	 */
	public function load_page()
	{
		SiteManager::load_page();
	}
	
	/**
	 * load module content (render)
	 */
	public function load_content()
	{
		
	}
	
	/**
	 * output module widget
	 */
	public function load_widget()
	{
		
	}
	
	/**
	 * dynamically generate header menu link text
	 * 
	 * @return string
	 */
	public function header_menu_text_dynamic()
	{
		return '';
	}
	
	public function header_menu_href_dynamic()
	{
		return '';
	}
	
	/**
	 * get widget display order
	 * 
	 * @return int
	 */
	final public function widget_display_order()
	{
		return $this->_row['widget_display_order'];
	}
	
	/**
	 * get header menu text from database or dynamic text function
	 * 
	 * @return string
	 */
	final public function header_menu_text()
	{
		if (empty($this->_row['header_menu_display_text']))
		{
			return $this->header_menu_text_dynamic();
		}
		else
		{
			return $this->_row['header_menu_display_text'];
		}
	}
	
	/**
	 * get header menu link href
	 * 
	 * @return string
	 */
	final public function header_menu_href()
	{
		if (empty($this->_row['header_menu_href'])) {
			return $this->header_menu_href_dynamic();
		} else {
			return $this->_row['header_menu_href'];
		}
	}
	
	/**
	 * get header menu link display order
	 * 
	 * @return int
	 */
	final public function header_menu_display_order()
	{		
		return $this->_row['header_menu_display_order'];
	}
}