<style type="text/css">
	.user_addnew_form
	{
		display: none;
	}
</style>

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

<h1 class="page-header">Manage Users</h1>

<form action="?module=user&amp;m=process_user" method="post" class="form-horizontal">
		<div class="listitem well">
			<input type="hidden" id="user_addnew_editor_user_id" name="user_addnew_editor_user_id" value="0" />
			<a class="user_addnew_link btn"><i class="icon-plus"></i> Add User</a>
			<div class="user_addnew_form">
				<div class="control-group">
					<label class="control-label" for="user_addnew_username">username: </label>
					<div class="controls">
						<input type="text" id="user_addnew_username" name="user_addnew_username" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="user_addnew_password">password: </label>
					<div class="controls">
						<input type="password" id="user_addnew_password" name="user_addnew_password" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="user_addnew_password_confirm">confirm: </label>
					<div class="controls">
						<input type="password" id="user_addnew_password_confirm" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="user_addnew_isadmin">is admin: </label>
					<div class="controls">
						<input type="checkbox" id="user_addnew_isadmin" name="user_addnew_isadmin" />
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<input type="submit" id="user_addnew_submit" name="user_addnew_submit" value="submit" class="btn btn-primary" />
					</div>
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
		echo 	'<div class="listitem well">' . "\n" .
				    '<input type="hidden" class="user_id" value="' . $row['user_id'] . '" />' . "\n" . 
				    '<input type="hidden" class="user_is_admin" value="' . $row['is_admin'] . '" />' . "\n" . 
				    '<div class="row-fluid">' . "\n" . 
					    '<div class="span10">' . "\n" . 
							'<div class="user_name">' . $row['username'] . '</div>' . "\n" . 
						'</div>' . "\n" . 
						'<div class="span2" style="text-align: right;">' . "\n" . 
							'<a class="user_delete btn"><i class="icon-remove"></i></a>' . "\n" .
							'<a class="user_edit btn"><i class="icon-edit"></i></a>' . "\n" . 
						'</div>' . "\n" . 
					'</div>' . "\n" . 
				'</div>' . "\n";
		$row = DataManager::fetch_array($result);
	}
}
?>
	</div>
</form>