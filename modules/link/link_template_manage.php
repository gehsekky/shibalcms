<style type="text/css">
	.link_delete, .link_category_delete
	{
		width: 18px;
		height: 18px;
		float: right;
		background: url('images/icon-red-x.png') no-repeat;
	}

	.link_edit, .link_category_edit
	{
		width: 18px;
		height: 18px;
		float: right;
		background: url('images/icon-edit.gif');
	}
	
	#link_addnew_name
	{
		width: 300px;
	}
	
	#link_addnew_displayorder
	{
		width: 50px;
	}
	
	#link_addnew_description
	{
		width: 400px;
		height: 100px;
	}
	
	.link_addnew_form, .link_category_addnew_form
	{
		display: none;
		position: relative;
	}
	
	.listitem
	{
		position: relative;
	}
	
	.link_text
	{
		width: 90%;
		float: left;
	}
	
	.link_description
	{
		clear: both;
	}
	
	.link_addnew_text
	{
		width: 400px;
	}
	
	.link_addnew_formlabel
	{
		display: block;
		width: 120px;
		text-align: right;
		float: left;
	}
	
	.listitem_category
	{
		border: 0px;
		font-weight: bold;
	}
	
	.category_container
	{
		min-height: 50px;
	}
	
	.link_addnew_form_row
	{
		padding: 5px 0px;
	}
	
	.meow
	{
		z-index: 10000;
	}
</style>

<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$(function () {

		$('.linktabs').tabs();
		$('.link_button').button();

		$('.link_addnew_link').on('click', function (e) {
			var $listitem = $(this).closest('.listitem');
			$('.link_addnew_form', $listitem).toggle();
			if ($('#link_addnew_editor_category_id').val() !== '0') {
				reset_linkform();
			}
		});

		$('.category_listitem_container').sortable({
			items: '.listitem',
			opacity: 0.5,
			zIndex: 5,
			start: function (event, ui) {
				$(ui.item).data('source_display_order', $(ui.item).index());
			},
			stop: function (event, ui) {
				var movePayload = {
					method: 'move_category',
					categoryId: $('.link_category_id', $(ui.item)).val(),
					sourceDisplayOrder: $(ui.item).data('source_display_order'),
					targetDisplayOrder: $(ui.item).index()
				};

				if (movePayload.sourceDisplayOrder !== movePayload.targetDisplayOrder) {
					$.ajax({
						type: 'post',
						url: 'modules/link/link_webmethods.php',
						contentType: 'application/x-www-form-urlencoded', 
						dataType: 'json', 
						data: movePayload,
						done: function (data, status, xhr) {
							
						}, 
						fail: function (xhr, status, err) {
	
						}, 
						always: function (xhr, status) {
	
						}
					});
				}
			}
		});
		
		$('.category_container').sortable({
			connectWith: '.category_container',
			items: '.listitem_link',
			opacity: 0.5,
			zIndex: 5,
			start: function (event, ui) {
				$(ui.item).data('source_category_id', $('.link_category_id', ui.item).val());
				$(ui.item).data('source_display_order', $(ui.item).index() - 1);
			},
			stop: function (event, ui) {
				var movePayload = {
					method: 'move_link',
					linkId: $('.link_id', $(ui.item)).val(),
					sourceCategoryId: $(ui.item).data('source_category_id'),
					targetCategoryId: $(ui.item).siblings('.listitem_category').find('.link_category_id').val(),
					sourceDisplayOrder: $(ui.item).data('source_display_order'),
					targetDisplayOrder: $(ui.item).index() - 1
				};

				// check if we're in another category
				if (movePayload.sourceCategoryId !== movePayload.targetCategoryId) {
					// change hidden category id field
					$('.link_category_id', ui.item).val(movePayload.targetCategoryId);
				}

				$.ajax({
					type: 'post',
					url: 'modules/link/link_webmethods.php',
					contentType: 'application/x-www-form-urlencoded', 
					dataType: 'json', 
					data: movePayload,
					done: function (data, status, xhr) {
						
					}, 
					fail: function (xhr, status, err) {

					}, 
					always: function (xhr, status) {

					}
				});
			}
		});
		
		$(document).on('click', '.link_delete', function (e) {
			var $parent = $(this).closest('.listitem');
			var $link_id = $('.link_id', $parent).val();
			if ($link_id > 0) {
				$('#master_dialog')
					.dialog('destroy')
					.html('are you sure you want to delete?')
					.dialog({
						title: 'confirm',
						modal: true, 
						buttons: {
							'delete': function () {
								var deletePayload = {
									method: 'delete_link', 
									link_delete_id: $link_id
								};
								
								$.ajax({
									type: 'post',
									url: 'modules/link/link_webmethods.php',
									contentType: 'application/x-www-form-urlencoded', 
									dataType: 'json', 
									data: deletePayload, 
									success: function(msg) {
										$parent.fadeOut('slow').remove();
										$.meow({
											title: 'success',
											message: 'link deleted', 
											icon: 'js/jquery/plugins/meow/nyan-cat.gif'
										});
									}, 
									error: function(xhr, status, err) {
										$.meow({
											title: 'error',
											message: 'an error occurred while deleting link', 
											icon: 'js/jquery/plugins/meow/nyan-cat.gif'
										});
									}
								});
								$(this).dialog('close');
							},
							'cancel': function () {
								$(this).dialog('close');
							}
						}
					});
			} else {
				$parent.fadeOut('slow').remove();
			}
		}).on('click', '.link_edit', function (e) {
			var $parent = $(this).closest('.listitem');
			var $link_id = $('.link_id', $parent).val();
			if ($link_id > 0) {
				reset_linkform();
				$('#link_addnew_editor_link_id').val($('.link_id', $parent).val());
				$('#link_addnew_href').val($('.link_text a', $parent).attr('href'));
				$('#link_addnew_text').val($('.link_text a', $parent).html());
				$('#link_addnew_category').val($('.link_category_id', $parent).val());
				$('#link_addnew_description').val($('.link_description', $parent).text());
				$('.link_addnew_form').show();
			}
		});

		$('#link_addnew_submit').on('click', function (e) {
			if ($('#link_addnew_editor_link_id').val() === '') {
				$.meow({
					title: 'validation error',
					message: 'link id is empty', 
					icon: 'js/jquery/plugins/meow/nyan-cat.gif'
				});
				return false;
			}
			if ($('#link_addnew_href').val() === '') {
				$.meow({
					title: 'validation error',
					message: 'link href is empty', 
					icon: 'js/jquery/plugins/meow/nyan-cat.gif'
				});
				return false;
			}
			if ($('#link_addnew_text').val() === '') {
				$.meow({
					title: 'validation error',
					message: 'link text is empty', 
					icon: 'js/jquery/plugins/meow/nyan-cat.gif'
				});
				return false;
			}
			if ($('#link_addnew_category').val() === '') {
				$.meow({
					title: 'validation error',
					message: 'link category not specified', 
					icon: 'js/jquery/plugins/meow/nyan-cat.gif'
				});
				return false;
			}
			return true;
		});
		$('.link_category_addnew_link').on('click', function (e) {
			var $listitem = $(this).closest('.listitem');
			$('.link_category_addnew_form', $listitem).toggle();
			if ($('#link_category_addnew_editor_category_id').val() !== '0') {
				reset_linkcategoryform();
			}
		});

		// TODO popup asking for confirmation / implement webmethod.
		$(document).live('click', '.link_category_delete', function (e) {
			var $parent = $(this).closest('.listitem');
			var $link_category_id = $('.link_category_id', $parent).val();
			if ($link_category_id > 0) {
				$.ajax({
					type: 'post',
					url: 'modules/links/link_webmethods.php',
					dataType: 'html', 
					data: {method: 'delete_category', link_category_delete_id: $link_category_id}, 
					success: function(msg) {
						$parent.fadeOut('slow').remove();
					}, 
					error: function(xhr, status, err) {
						console.log('error');
						console.log(xhr);
						console.log(status);
						console.log(err);
					}
				});
			} else {
				$parent.fadeOut('slow').remove();
			}
		}).on('click', '.link_category_edit', function (e) {
			var $parent = $(this).closest('.listitem');
			var $links_id = $('.link_category_id', $parent).val();
			if ($links_id > 0) {
				reset_linkcategoryform();
				$('#link_category_addnew_editor_category_id').val($links_id);
				$('#link_category_addnew_name').val($('.link_text', $parent).text());
				$('.link_category_addnew_form').show();
			}
		});

		$('#link_category_addnew_submit').on('click', function (e) {
			if ($('#link_category_addnew_name').val === '') {
				return false;
			}
			return true;
		});
	});

	function reset_linkcategoryform()
	{
		$('#link_category_addnew_editor_category_id').val('0');
		$('#link_category_addnew_name').val('');
	}

	function reset_linkform()
	{
		$('#link_addnew_editor_link_id').val('0');
		$('#link_addnew_href').val('');
		$('#link_addnew_text').val('');
		$('#link_addnew_category').val('');
		$('#link_addnew_description').val('');
	}
</script>

<h1 class="page_content_title">Manage Links</h1>

<div class="linktabs">
	
	<ul>
		<li><a href="#tab_link">links</a></li>
		<li><a href="#tab_category">categories</a></li>
	</ul>
	<div id="tab_link">
		<form action="?module=link&m=process_link" method="post">
			<div class="listitem">
				<input type="hidden" id="link_addnew_editor_link_id" name="link_addnew_editor_link_id" value="0" />
				<div class="link_addnew_link" style="height: 20px; background: url('images/new_icon.png') no-repeat;"><span style="margin-left: 20px;">Link</span></div>
				<div class="link_addnew_form">
					<div class="link_addnew_form_row"><span class="link_addnew_formlabel">href: </span><input type="text" id="link_addnew_href" name="link_addnew_href" class="link_addnew_text" /></div>
					<div class="link_addnew_form_row"><span class="link_addnew_formlabel">text: </span><input type="text" id="link_addnew_text" name="link_addnew_text" class="link_addnew_text" /></div>
					<div class="link_addnew_form_row"><span class="link_addnew_formlabel">description: </span><textarea id="link_addnew_description" name="link_addnew_description"></textarea></div>
					<div class="link_addnew_form_row">
						<span class="link_addnew_formlabel">category: </span>
						<select id="link_addnew_category" name="link_addnew_category">
							<option value="">choose</option>
<?php 
$sql = 'select link_category_id, name from link_category order by display_order, name';
$result = DataManager::query($sql);
if ($result) {
	$row = DataManager::fetch_array($result);
	while ($row) {
		echo '<option value="' . $row['link_category_id'] . '">' . $row['name'] . '</option>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>
						</select>
					</div>
					<div class="link_addnew_form_row">
						<span class="link_addnew_formlabel">&nbsp;</span>
						<input type="submit" class="link_button" id="link_addnew_submit" name="link_addnew_submit" value="submit" />
					</div>
				</div>
			</div>
<?php 
$params = SiteManager::get_querystring_params();
$sql = 'select link_category_id, name, display_order from link_category order by display_order asc';
$categories = DataManager::query($sql);
if ($categories) {
	$category = DataManager::fetch_array($categories);
	while ($category) {
		echo '<div class="category_container">' . "\n";
		echo '<div class="listitem listitem_category clearfix">' . "\n" . 
				'<input type="hidden" class="link_category_id" value="' . $category['link_category_id'] . '" />' . "\n" . 
				'<input type="hidden" class="link_category_displayorder" value="' . $category['display_order'] . '" />' . "\n" . 
				'<div class="link_text"><span class="link_category_name">' . $category['name'] . '</span></div>' . "\n" . 
				'</div>' . "\n";
		$sql = 'select link_id, href, text, link_category_id, description ' . 
				'from link ' . 
				'where link_category_id = ' . $category['link_category_id'] . ' ' . 
				'order by display_order asc';
		$result = DataManager::query($sql);
		if ($result) {
			$row = DataManager::fetch_array($result);
			while ($row) {
				// display category
				echo '<div class="listitem listitem_link clearfix">' . "\n" .
					    '<input type="hidden" class="link_id" value="' . $row['link_id'] . '" />' . "\n" . 
					    //'<input type="hidden" class="link_displayorder" value="' . $row['display_order'] . '" />' . "\n" . 
					    '<input type="hidden" class="link_category_id" value="' . $row['link_category_id'] . '" />' . "\n" . 
						'<div class="link_text"><a href="' . $row['href'] . '" target="_blank">' . $row['text'] . '</a></div>' . "\n" . 
						(array_key_exists('m', $params) && $params['m'] == 'manage_link' ? '<div class="link_delete">&nbsp;</div>' . "\n" : '') .
						(array_key_exists('m', $params) && $params['m'] == 'manage_link' ? '<div class="link_edit">&nbsp;</div>' . "\n" : '') .
						'<div class="link_description">' . $row['description'] . '</div>' . "\n" . 
						'</div>' . "\n";
				$row = DataManager::fetch_array($result);
			}
		}
		echo '</div>' . "\n";
		$category = DataManager::fetch_array($categories);
	}
}
?>
			</form>
		</div>
		<div id="tab_category">
			<form action="?module=link&m=process_category" method="post">
			<div class="listitem">
				<input type="hidden" id="link_category_addnew_editor_category_id" name="link_category_addnew_editor_category_id" value="0" />
				<div class="link_category_addnew_link" style="height: 20px; background: url('images/new_icon.png') no-repeat;"><span style="margin-left: 20px;">Category</span></div>
				<div class="link_category_addnew_form">
					<div class="link_addnew_form_row">
						<span class="link_addnew_formlabel">name: </span>
						<input type="text" id="link_category_addnew_name" name="link_category_addnew_name" />
					</div>
					<div class="link_addnew_form_row">
						<span class="link_addnew_formlabel">&nbsp;</span>
						<input type="submit" class="link_button" id="link_category_addnew_submit" name="link_category_addnew_submit" value="submit" />
					</div>
				</div>
			</div>
			<div class="category_listitem_container">
<?php 
$params = SiteManager::get_querystring_params();
$sql = 'select link_category_id, name from link_category order by display_order asc';
$result = DataManager::query($sql);
if ($result) {
	$row = DataManager::fetch_array($result);
	while ($row) {
		// display category
		echo 	'<div class="listitem clearfix">' . "\n" .
				    '<input type="hidden" class="link_category_id" value="' . $row['link_category_id'] . '" />' . "\n" . 
					'<div class="link_text">' . $row['name'] . '</div>' . "\n" . 
					(array_key_exists('m', $params) && $params['m'] == 'manage_link' ? '<div class="link_category_delete">&nbsp;</div>' . "\n" : '') .
					(array_key_exists('m', $params) && $params['m'] == 'manage_link' ? '<div class="link_category_edit">&nbsp;</div>' . "\n" : '') .
				'</div>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>
			</div>
		</form>
	</div>
</div>