<?php 
require_once '../core/sqlparser.php';

if ($_SERVER['QUERY_STRING'] == 'install') {
	// set up database
	$parser = new SqlParser('shibalcms.sql');
	$parser->Parse();	
	$parser->Execute();
	
	// insert admin user record
	
	// direct to login page
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