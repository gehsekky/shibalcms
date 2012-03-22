<?php
require_once '../../config.php';
require_once '../../core/datamanager.php';
require_once '../../core/usermanager.php';

if (!UserManager::is_admin())
{
	header('Location: ../../?');
}

$method = null;
if (isset($_REQUEST['method'])) {
	$method = $_REQUEST['method'];
}

if ($method != null) {
	switch ($method) {
		case 'delete_link':
			if (isset($_REQUEST['link_delete_id']) && $_REQUEST['link_delete_id'] > 0) {
				$sql = sprintf('select * from link where link_id = %d', DataManager::sanitize($_REQUEST['link_delete_id']));
				$result = DataManager::query($sql);
				if ($result) {
					$delete_link = DataManager::fetch_array($result);
					if ($delete_link) {
						$sql = sprintf(	'update link ' .
										'set display_order = display_order - 1 ' . 
										'where link_category_id = %d ' . 
										'and display_order > %d',
										DataManager::sanitize($delete_link['link_category_id']),
										DataManager::sanitize($delete_link['display_order']));
						if (DataManager::query($sql)) {
							$sql = sprintf('delete from link where link_id = %d', DataManager::sanitize($_REQUEST['link_delete_id']));
							if (DataManager::query($sql)) {
								header('HTTP/1.1 200 OK');
								break;
							}						
						}
					}
				}
			}
			header('HTTP/1.1 500 Internal Server Error');
			break;
		case 'move_link':
			if (isset($_REQUEST['linkId']) && $_REQUEST['linkId'] > 0) {
				$linkId = intval($_REQUEST['linkId']);
				$sourceCategoryId = intval($_REQUEST['sourceCategoryId']);
				$sourceDisplayOrder = intval($_REQUEST['sourceDisplayOrder']);
				$targetCategoryId = intval($_REQUEST['targetCategoryId']);
				$targetDisplayOrder = intval($_REQUEST['targetDisplayOrder']);
				
				// update source links
				$sql = sprintf(	'update link set ' .
								'display_order = display_order - 1 ' . 
								'where link_category_id = %d ' . 
								'and display_order > %d',
								DataManager::sanitize($sourceCategoryId),
								DataManager::sanitize($sourceDisplayOrder));

				if (DataManager::query($sql)) {
					// update target links
					$sql = sprintf(	'update link set ' .
									'display_order = display_order + 1 ' . 
									'where link_category_id = %d ' . 
									'and display_order >= %d',
									DataManager::sanitize($targetCategoryId),
									DataManager::sanitize($targetDisplayOrder));
					
					if (DataManager::query($sql)) {
						// update link
						$sql = sprintf(	'update link set ' . 
										'display_order = %d, ' . 
										'link_category_id = %d ' . 
										' where link_id = %d',
										DataManager::sanitize($targetDisplayOrder), 
										DataManager::sanitize($targetCategoryId),
										DataManager::sanitize($linkId));
						if (DataManager::query($sql)) {
							header('HTTP/1.1 200 OK');
							break;
						}
					}
				}
			}
			header('HTTP/1.1 500 Internal Server Error');
			break;
		case 'move_category':
			if (isset($_REQUEST['categoryId']) && $_REQUEST['categoryId'] > 0) {
				$categoryId = intval($_REQUEST['categoryId']);
				$sourceDisplayOrder = intval($_REQUEST['sourceDisplayOrder']);
				$targetDisplayOrder = intval($_REQUEST['targetDisplayOrder']);
			
				// update source links
				$sql = sprintf(	'update link_category set ' .
								'display_order = display_order - 1 ' . 
								'where display_order > %d',
				DataManager::sanitize($sourceDisplayOrder));
			
				if (DataManager::query($sql)) {
					// update target links
					$sql = sprintf(	'update link_category set ' .
									'display_order = display_order + 1 ' . 
									'where display_order >= %d',
					DataManager::sanitize($targetDisplayOrder));
						
					if (DataManager::query($sql)) {
						// update link
						$sql = sprintf(	'update link_category set ' .
										'display_order = %d ' . 
										'where link_category_id = %d',
						DataManager::sanitize($targetDisplayOrder),
						DataManager::sanitize($categoryId));
						if (DataManager::query($sql)) {
							header('HTTP/1.1 200 OK');
							break;
						}
					}
				}
			}
			header('HTTP/1.1 500 Internal Server Error');
			break;
		default:
			header('HTTP/1.1 400 Bad Request');
			break;
	}
}
?>