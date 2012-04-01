<h1 class="page-header">Login</h1>

<form name="login_form" id="login_form" class="form-horizontal" method="post" action="?module=login&m=process">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="login_username">username:</label>
			<div class="controls">
				<input type="text" class="input-large" id="login_username" name="login_username" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="login_password">password:</label>
			<div class="controls">
				<input type="password" class="input-large" id="login_password" name="login_password" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" id="login_button" class="btn btn-primary" value="submit" />
			</div>
		</div>
	</fieldset>
</form>

