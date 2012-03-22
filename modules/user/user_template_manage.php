<style type="text/css">
	.user_delete
	{
		width: 18px;
		height: 18px;
		float: right;
		background: url('images/icon-red-x.png') no-repeat;
	}

	.user_edit
	{
		width: 18px;
		height: 18px;
		float: right;
		background: url('images/icon-edit.gif');
	}
	
	#userpublish_headline
	{
		width: 500px;
	}
	
	#userpublish_content
	{
		width: 500px;
		height: 300px;
	}
	
	.user_addnew_form
	{
		display: none;
		position: relative;
	}
	
	.user_name
	{
		width: 80%;
		float: left;
	}
	
	.user_addnew_formlabel
	{
		display: block;
		width: 100px;
		text-align: right;
		float: left;
	}
	
	.user_addnew_form_row
	{
		padding: 5px 0px;
	}
</style>

<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
	$(function () {

		$('.user_addnew_link').on('click', function (e) {
			$('.user_addnew_form').toggle();
			if ($('#user_addnew_editor_user_id').val() !== '0') {
				reset_userform();
			}
		});

		// TODO popup asking for confirmation.
		$(document).on('click', '.user_delete', function (e) {
			var $parent = $(this).closest('.listitem');
			var user_id = $('.user_id', $parent).val();
			if (user_id > 0) {
				$.ajax({
					type: 'post',
					url: 'modules/user/user_webmethods.php',
					dataType: 'html', 
					data: {method: 'delete', delete_id: user_id}, 
					success: function(msg) {
						$parent.fadeOut('slow').remove();
					}, 
					error: function(xhr, status, err) {
						console.log('error');
					}
				});
			} else {
				$parent.fadeOut('slow').remove();
			}
		}).on('click', '.user_edit', function (e) {
			var $parent = $(this).closest('.listitem');
			var user_id = $('.user_id', $parent).val();
			if (user_id > 0) {
				reset_userform();
				$('#user_addnew_editor_user_id').val(user_id);
				$('#user_addnew_username').val($('.user_name', $parent).text());
				if ($('.user_is_admin', $parent).val() === '1') {
					$('#user_addnew_isadmin').prop('checked', true);
				}
				$('.user_addnew_form').show();
			}
		}).on('click', '.user_delete', function (e) {
			var $parent = $(this).closest('.listitem');
			var user_id = $('.user_id', $parent).val();
			if (user_id > 0) {
				$.ajax({
					type: 'post',
					url: 'modules/user/user_webmethods.php',
					dataType: 'html', 
					data: {method: 'delete', delete_id: user_id}, 
					success: function(msg) {
						$parent.fadeOut('slow').remove();
					}, 
					error: function(xhr, status, err) {
						console.log('error');
					}
				});
			} else {
				$parent.fadeOut('slow').remove();
			}
		});

		$('#user_addnew_submit').on('click', function (e) {
			if ($('#user_addnew_username').val === '') {
				return false;
			}
			if ($('#user_addnew_password').val() !== '') {
				if ($('#user_addnew_password').val() !== $('#user_addnew_password_confirm').val()) {
					return false;
				}
			}
			if ($('#user_addnew_editor_user_id').val() === '0') {
				if ($('#user_addnew_password').val() === '' || $('#user_addnew_username').val === '') {
					return false;
				}
			}
			return true;
		}).button();

		$('.listitem_container').listerine({
			transform: 'grid', 
			listitem_style: {
				'width': '200px',
				'min-height': '50px', 
				'margin': '0px 10px 10px 0px', 
				'background-color': '#eee'
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

	function reset_userform()
	{
		$('#user_addnew_editor_user_id').val('0');
		$('#user_addnew_username').val('');
		$('#user_addnew_password').val('');
		$('#user_addnew_password_confirm').val('');
		$('#user_addnew_isadmin').prop('checked', false);
	}
</script>

<h1 class="page_content_title">Manage Users</h1>

<form action="?module=user&amp;m=process_user" method="post">
		<div class="listitem">
			<input type="hidden" id="user_addnew_editor_user_id" name="user_addnew_editor_user_id" value="0" />
			<div class="user_addnew_link" style="width: 20px; height: 20px; background: url('images/new_icon.png') no-repeat;">&nbsp;</div>
			<div class="user_addnew_form">
				<div class="user_addnew_form_row"><span class="user_addnew_formlabel">username: </span><input type="text" id="user_addnew_username" name="user_addnew_username" /></div>
				<div class="user_addnew_form_row"><span class="user_addnew_formlabel">password: </span><input type="password" id="user_addnew_password" name="user_addnew_password" /></div>
				<div class="user_addnew_form_row"><span class="user_addnew_formlabel">confirm: </span><input type="password" id="user_addnew_password_confirm" /></div>
				<div class="user_addnew_form_row"><span class="user_addnew_formlabel">is admin: </span><input type="checkbox" id="user_addnew_isadmin" name="user_addnew_isadmin" /></div>
				<div class="user_addnew_form_row">
					<span class="user_addnew_formlabel">&nbsp;</span>
					<input type="submit" id="user_addnew_submit" name="user_addnew_submit" value="submit" />
				</div>
			</div>
		</div>
		<div class="listitem_container">
<?php 
$params = SiteManager::get_querystring_params();
$sql = 'select user_id, username, is_admin from user order by username desc';
$result = DataManager::query($sql);
if ($result) {
	$row = DataManager::fetch_array($result);
	while ($row) {
		echo 	'<div class="listitem clearfix">' . "\n" .
				    '<input type="hidden" class="user_id" value="' . $row['user_id'] . '" />' . "\n" . 
				    '<input type="hidden" class="user_is_admin" value="' . $row['is_admin'] . '" />' . "\n" . 
					'<div class="user_name">' . $row['username'] . '</div>' . "\n" . 
					(array_key_exists('m', $params) && $params['m'] == 'manage' ? '<div class="user_delete">&nbsp;</div>' . "\n" : '') .
					(array_key_exists('m', $params) && $params['m'] == 'manage' ? '<div class="user_edit">&nbsp;</div>' . "\n" : '') . 
				'</div>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>
	</div>
</form>