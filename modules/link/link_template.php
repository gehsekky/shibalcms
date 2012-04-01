<style type="text/css">
	.listitem_category
	{
		font-size: 16px;
		font-weight: bold;
		padding: 10px;
	}
</style>

<h1 class="page-header">Links</h1>

<?php 
$params = SiteManager::get_querystring_params();
$sql = 'select link_category_id, name, display_order from link_category order by display_order, name desc';
$categories = DataManager::query($sql);
if ($categories) {
	$category = DataManager::fetch_array($categories);
	while ($category) {
		echo 	'<div class="listitem listitem_category">' . "\n" . 
					'<input type="hidden" class="link_cateogory_id" value="' . $category['link_category_id'] . '" />' . "\n" . 
					'<input type="hidden" class="link_category_displayorder" value="' . $category['display_order'] . '" />' . "\n" . 
					'<div class="link_text"><span class="link_category_name">' . $category['name'] . '</span></div>' . "\n" . 
				'</div>' . "\n";
		echo 	'<div class="category_container">' . "\n";
		
		$sql = 	'select link_id, href, text, link_category_id, display_order, description ' . 
				'from link ' . 
				'where link_category_id = ' . $category['link_category_id'] . ' ' . 
				'order by display_order asc';
		$result = DataManager::query($sql);
		if ($result) {
			$row = DataManager::fetch_array($result);
			while ($row) {
				// display category
				echo 	'<div class="well listitem listitem_link">' . "\n" .
						    '<input type="hidden" class="link_id" value="' . $row['link_id'] . '" />' . "\n" . 
							'<div class="link_text"><a href="' . $row['href'] . '" target="_blank">' . $row['text'] . '</a></div>' . "\n" . 
							'<div class="link_description">' . $row['description'] . '</div>' . "\n" . 
						'</div>' . "\n";
				$row = DataManager::fetch_array($result);
			}
		}
		echo 	'</div>' . "\n";
		$category = DataManager::fetch_array($categories);
	}
}
?>