<style type="text/css">
/*
	.link_delete, .link_category_delete
	{
		float: right;
	}

	.link_edit, .link_category_edit
	{
		float: right;
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
*/
	.link_addnew_form, .link_category_addnew_form
	{
		display: none;
	}
	
	.meow
	{
		z-index: 10000;
	}
</style>

<script type="text/javascript" src="js/bootstrap/js/bootstrap-tab.js"></script>
<script type="text/javascript">
	$(function () {

		$('.link_addnew_link').on('click', function (e) {
			var $listitem = $(this).closest('.well');
			$('.link_addnew_form', $listitem).toggle();
			if ($('#link_addnew_editor_category_id').val() !== '0') {
				reset_linkform();
			}
		});

		$('.category_listitem_container').sortable({
			items: '.well',
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
						data: movePayload
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
					data: movePayload
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
			var $listitem = $(this).closest('.well');
			$('.link_category_addnew_form', $listitem).toggle();
			if ($('#link_category_addnew_editor_category_id').val() !== '0') {
				reset_linkcategoryform();
			}
		});

		// TODO popup asking for confirmation / implement webmethod.
		$(document).on('click', '.link_category_delete', function (e) {
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

<h1 class="page-header">Manage Links</h1>

<div class="linktabs">
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_link" data-toggle="tab">links</a></li>
		<li><a href="#tab_category" data-toggle="tab">categories</a></li>
	</ul>
	<div class="tab-content">
	<div class="tab-pane active" id="tab_link">
		<form action="?module=link&m=process_link" method="post" class="form-horizontal">
			<fieldset>
			<div class="well">
				<input type="hidden" id="link_addnew_editor_link_id" name="link_addnew_editor_link_id" value="0" />
				<a class="link_addnew_link btn"><i class="icon-plus"></i> Add Link</a>
				<div class="link_addnew_form">
					<div class="control-group">
						<label class="control-label" for="link_addnew_href">href: </label>
						<div class="controls">
							<input type="text" id="link_addnew_href" name="link_addnew_href" class="link_addnew_text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="link_addnew_text">text: </label>
						<div class="controls">
							<input type="text" id="link_addnew_text" name="link_addnew_text" class="link_addnew_text" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="link_addnew_description">description: </label>
						<div class="controls">
							<textarea id="link_addnew_description" name="link_addnew_description"></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="link_addnew_category">category: </label>
						<div class="controls">
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
					</div>
					<div class="control-group">
						<div class="controls">
							<input type="submit" class="link_button btn btn-primary" id="link_addnew_submit" name="link_addnew_submit" value="submit" />
						</div>
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
		echo '<div class="listitem listitem_category">' . "\n" . 
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
				echo 	'<div class="well listitem listitem_link">' . "\n" .
						    '<input type="hidden" class="link_id" value="' . $row['link_id'] . '" />' . "\n" . 
						    '<input type="hidden" class="link_category_id" value="' . $row['link_category_id'] . '" />' . "\n" .
						    '<div class="row-fluid">' . "\n" .  
						    	'<div class="span10">' . "\n" . 
									'<div class="link_text"><a href="' . $row['href'] . '" target="_blank">' . $row['text'] . '</a></div>' . "\n" .
									'<div class="link_description">' . $row['description'] . '</div>' . "\n" .
								'</div>' . "\n" .  
								'<div class="span2" style="text-align: right;">' . "\n" . 
									'<a class="link_edit btn"><i class="icon-edit"></i></a>' . "\n" . 
									'<a class="link_delete btn"><i class="icon-minus"></i></a>' . "\n" . 
								'</div>' . "\n" . 
							'</div>' . "\n" . 
						'</div>' . "\n";
				$row = DataManager::fetch_array($result);
			}
		}
		echo '</div>' . "\n";
		$category = DataManager::fetch_array($categories);
	}
}
?>
			</fieldset>
			</form>
		</div>
		<div class="tab-pane" id="tab_category">
			<form action="?module=link&m=process_category" method="post" class="form-horizontal">
			<fieldset>
			<div class="well">
				<input type="hidden" id="link_category_addnew_editor_category_id" name="link_category_addnew_editor_category_id" value="0" />
				<a class="link_category_addnew_link btn"><i class="icon-plus"></i> Add Category</a>
				<div class="link_category_addnew_form">
					<div class="control-group">
						<label class="control-label">name: </label>
						<div class="controls">
							<input type="text" id="link_category_addnew_name" name="link_category_addnew_name" class="input-large" />
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<input type="submit" class="link_button btn btn-primary" id="link_category_addnew_submit" name="link_category_addnew_submit" value="submit" />
						</div>
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
		echo 	'<div class="well listitem">' . "\n" .
				    '<input type="hidden" class="link_category_id" value="' . $row['link_category_id'] . '" />' . "\n" .
				    '<div class="row-fluid">' . "\n" .  
						'<div class="span10">' . "\n" . 
							'<div class="link_text">' . $row['name'] . '</div>' . "\n" . 
						'</div>' . "\n" . 
						'<div class="span2" style="text-align: right;">' . "\n" . 
							'<a class="link_category_delete btn"><i class="icon-minus"></i></a>' . "\n" .
							'<a class="link_category_edit btn"><i class="icon-edit"></i></a>' . "\n" .
						'</div>' . "\n" . 
					'</div>' . "\n" . 
				'</div>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>
			</div>
			</fieldset>
		</form>
	</div>
	</div>
</div>