<?php
require_once 'core/module.php';
require_once 'core/sitemanager.php';
require_once 'core/usermanager.php';

class linkModule extends Module
{
	public function load_page()
	{
		$params = SiteManager::get_querystring_params();
		if (array_key_exists('m', $params)) {
			switch($params['m']) {
				case 'process_link':
					if (UserManager::is_admin()) {
						if (isset($_REQUEST['link_addnew_editor_link_id'])) {
							$link_id = $_REQUEST['link_addnew_editor_link_id'];
							$link_href = trim($_REQUEST['link_addnew_href']);
							$link_text = trim($_REQUEST['link_addnew_text']);
							$link_category = trim($_REQUEST['link_addnew_category']);
							$link_displayorder = trim($_REQUEST['link_addnew_displayorder']);
							$link_description = trim($_REQUEST['link_addnew_description']);
							if ($link_id == 0) {
								$sql = sprintf('insert into link ' . 
												'(href, text, link_category_id, display_order, created_on, user_id, description) ' . 
												'select \'%s\', \'%s\', %d, count(link_id), now(), %d, \'%s\' from link ' . 
												'where link_category_id = %d',
												DataManager::sanitize($link_href),
												DataManager::sanitize($link_text),
												DataManager::sanitize($link_category),
												DataManager::sanitize(UserManager::current_user_id()), 
												DataManager::sanitize($link_description), 
												DataManager::sanitize($link_category));
							} else {
								$sql = sprintf('update link set ' . 
												'href = \'%s\', ' . 
												'text = \'%s\', ' . 
												'link_category_id = %d, ' .
												'description = \'%s\' ' . 
												'where link_id = %d',
												DataManager::sanitize($link_href),
												DataManager::sanitize($link_text),
												DataManager::sanitize($link_category),
												DataManager::sanitize($link_description), 
												DataManager::sanitize($link_id));
							}
							if (!DataManager::query($sql)) {
								header('Location: ?module=link&m=manage_link&error=1');
							} else {
								header('Location: ?module=link&m=manage_link');
							}
						}
					} else {
						header('Location: ?');
					}
					break;
				case 'process_category':
					if (UserManager::is_admin()) {
						if (isset($_REQUEST['link_category_addnew_editor_category_id'])) {
							$category_id = $_REQUEST['link_category_addnew_editor_category_id'];
							$category_name = trim($_REQUEST['link_category_addnew_name']);
							if ($category_id == 0) {
								// insert
								$sql = sprintf(	'insert into link_category (name, display_order) ' . 
												'select \'%s\', count(link_category_id) from link_category', 
												DataManager::sanitize($category_name));
								echo "$sql<br />\n";
							} else {
								// update
								$sql = sprintf(	'update link_category set ' . 
												'name = \'%s\' ' . 
												'where link_category_id = %d',  
												DataManager::sanitize($category_name), 
												DataManager::sanitize($category_id));
								echo "$sql<br />\n";
							}
							if (!DataManager::query($sql)) {
								header('Location: ?module=link&m=error');
							}
							header('Location: ?module=link&m=manage_link');
						}
					} else {
						header('Location: ?');
					}

					break;
				case 'manage_link':
					if (UserManager::is_admin()) {
						SiteManager::load_page();
					} else {
						header('Location: ?');
					}
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
				case 'manage_link':
					require_once 'modules/link/link_template_manage.php';
					break;
				case 'error':
					echo 'an error occurred during your request.';
					break;
				default:
					require_once 'modules/link/link_template.php';
			}
		} else {
			require_once 'modules/link/link_template.php';
		}
	}
	
	public function header_menu_text()
	{
		return 'links';
	}

	public function header_menu_display_order()
	{
		return 5;
	}
}
?>