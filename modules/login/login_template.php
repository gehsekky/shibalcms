<style type="text/css">
	#login_form_container
	{
		width: 500px;
		padding: 5px;
	}
	
	.login_control input
	{
		width: 300px;
	}
	
	.login_control_container
	{
		margin: 0px;
		padding: 5px;
		position: relative;
		vertical-align: middle;
	}
	
	.login_label
	{
		margin: 0px;
		padding: 5px;
		width: 75px;
		float: left;
		text-align: right;
	}
	
	.login_control
	{
		float: left;
	}
	
	.login_clear
	{
		clear: both;
	}
</style>

<h1 class="page_content_title">Login</h1>

<div id="login_form_container">
<form name="login_form" id="login_form" method="post" action="?module=login&m=process">

	<div class="login_control_container">
		<div class="login_label">username:</div><div class="login_control"><input type="text" id="login_username" name="login_username" /></div><div class="login_clear"></div>
	</div>
	<div class="login_control_container">
		<div class="login_label">password:</div><div class="login_control"><input type="password" id="login_password" name="login_password" /></div><div class="login_clear"></div>
	</div>
	<div class="login_control_container">
		<input type="submit" id="login_button" value="submit" />
	</div>
</form>
</div>