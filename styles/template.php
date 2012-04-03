<?php
require_once 'core/timermanager.php';
require_once 'core/datamanager.php';
require_once 'core/sitemanager.php';

$timer = new TimerManager();
$timer->start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Shibal CMS</title>
		
		<!-- styles -->
		<link rel="stylesheet" type="text/css" href="js/jquery-ui/css/black-tie/jquery-ui-1.8.18.custom.css" />
		<link rel="stylesheet" type="text/css" href="styles/default.css" />
		
		<!-- jquery -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		
		<!-- jquery plugins -->
		<script type="text/javascript" src="js/jquery/plugins/jquery.querystring.js"></script>
		<link rel="stylesheet" type="text/css" href="js/jquery/plugins/meow/jquery.meow.css" />
		<script type="text/javascript" src="js/jquery/plugins/meow/jquery.meow.js"></script>
		
		<!-- bootstrap -->
		<link rel="stylesheet" href="js/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="js/bootstrap/css/bootstrap-responsive.css" />
		<script type="text/javascript" src="js/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="js/bootstrap/js/bootstrap-modal.js"></script>
		<script type="text/javascript" src="js/jquery/plugins/jquery.bootstrapmodal.js"></script>

	</head>
	<body>

		<div id="shibal-header" class="navbar">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="?">Shibal CMS</a>
			
					<ul class="nav">
						<?php SiteManager::load_header_menu(); ?>
					</ul>
				</div>
			</div>
		</div>

		<div id="shibal-content" class="container-fluid">
			<div class="row-fluid">
				<div class="span2">
					<?php SiteManager::load_widgets(); ?>
				</div>
				<div class="span10">
					<?php SiteManager::load_content(); ?>
				</div>
			</div>
		</div>
		
 		<div id="shibal-footer" class="navbar">
 			<div class="span12">
				&copy; <?php echo date('Y');?> . <a href="?">shibalcms</a> . generated in 
				<?php
 					$timer->stop();
 					echo number_format($timer->duration(), 3) . 's';
 				?>
 			</div>
 		</div>
 				
		<div id="master_dialog" style="display: none;"></div>
	</body>
</html>