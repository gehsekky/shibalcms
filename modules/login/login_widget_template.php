<div class="widget">
	<?php if (!UserManager::logged_in()) { ?>
	<form class="form-vertical">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="login_username">Username</label>
				<div class="controls">
					<input type="text" class="span2" id="login_username" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="login_password">Password</label>
				<div class="controls">
					<input type="password" class="span2" id="login_password" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<a href="javascript:void(0);" class="btn">
						<i class="icon-user"></i> Login
					</a>
				</div>
			</div>
		</fieldset>
	</form>
	<?php } else { ?>
		Logged in as: <?php echo UserManager::current_username(); ?>
	<?php } ?>
</div>