<style type="text/css">
	.listitem_category
	{
		margin: 0px;
		border: 0px;
		font-weight: bold;
	}
	
	.link_description
	{
		font-size: 12px;
	}
</style>

<script type="text/javascript">
	$(function () {
		$('.category_container').listerine({
			transform: 'columns', 
			clearfix: true,
			listitem_style: {
				'margin': '0px 10px 10px 0px', 
				'background-color': '#eee', 
				'text-align' : 'left'
			}, 
			listitem_hover: {
				'in': function () {
					var $this = $(this);
					$this.css('background-color', '#fff');
				}, 
				'out': function () {
					var $this = $(this);
					$this.css('background-color', '#eee');
				}
			}
		});
	});
</script>

<h1 class="page_content_title">Links</h1>

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
				echo 	'<div class="listitem listitem_link">' . "\n" .
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