<?php 
require_once '../config.php';
require_once '../core/datamanager.php';
require_once '../core/sqlparser.php';
require_once '../core/usermanager.php';

if ($_SERVER['QUERY_STRING'] == 'install') {
	// set up database
	$parser = new SqlParser('shibalcms.sql');
	$parser->Parse();	
	$parser->Execute();
	
	// insert admin user record
	$username = isset($_REQUEST['username']) && $_REQUEST['username'] !== '' ? $_REQUEST['username'] : '';
	$password = isset($_REQUEST['password']) && $_REQUEST['password'] !== '' ? $_REQUEST['password'] : '';
	$is_admin = 1;
	$dynamic_salt = mt_rand();
	$remote_ip = $_SERVER['REMOTE_ADDR'];
	$sql = sprintf('insert into user ' .
					'(username, password, created_on, dynamic_salt, last_seen_ip, last_login, is_admin) ' .
					'values ' .
					'(\'%s\', sha1(concat(\'%s\', \'%s\', \'%s\')), now(), \'%s\', \'%s\', now(), \'%s\')',
					DataManager::sanitize($username), 
					DataManager::sanitize(SiteSettings::get('site_static_salt')),
					DataManager::sanitize($password), DataManager::sanitize($dynamic_salt), 
					DataManager::sanitize($dynamic_salt), DataManager::sanitize($remote_ip), 
					DataManager::sanitize($is_admin));
	if (!DataManager::query($sql)) {
		header('Location: ?error');
	}
	
	$sql = 	'insert into module ' . 
			'(name, header_menu_display_order, header_menu_display_text, ' . 
			'header_menu_href, widget_display_order) ' . 
			'values (\'about\', 0, \'home\', null, -1)' . 
			',(\'link\', 1, \'links\', \'?module=link\', -1)' . 
			',(\'login\', 2, null, null, 0)' . 
			',(\'admin\', 3, null, null, -1)';
	if (!DataManager::query($sql)) {
		header('Location: ?error');
	}
} else {
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Shibal CMS</title>
		<!-- styles -->
		<link rel="stylesheet" type="text/css" href="../js/jquery-ui/css/black-tie/jquery-ui-1.8.18.custom.css" />
		<link rel="stylesheet" type="text/css" href="../styles/default.css" />
		
		<!-- jquery -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		
		<!-- bootstrap -->
		<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="../js/bootstrap/css/bootstrap-responsive.css" />
		<script type="text/javascript" src="../js/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/bootstrap/js/bootstrap-modal.js"></script>
		<script type="text/javascript" src="../js/jquery/plugins/jquery.bootstrapmodal.js"></script>
	</head>
	<body>
	<form class="form-horizontal" method="post" action="?install">
		<div class="container">
			<h1 class="page-header">Welcome to ShibalCMS</h1>
			
			<fieldset>
				<legend>Admin Info</legend>
				<div class="control-group">
					<label class="control-label" for="username">username: </label>
					<div class="controls">
						<input type="text" class="input-medium" id="username" name="username" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">password: </label>
					<div class="controls">
						<input type="password" class="input-medium" id="password" name="password" />
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<input type="submit" id="install_button" class="btn btn-success btn-large" value="Install" />
					</div>
				</div>
			</fieldset>
		</div>
		</form>
	</body>
</html>
<?php } ?>