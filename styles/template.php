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
		<!-- do not change order of includes -->
		<!-- styles -->
		<link rel="stylesheet" type="text/css" href="styles/default.css" />
		
		<!-- jquery -->
		<script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>
	
		<!-- jquery plugins -->
		<link rel="stylesheet" type="text/css" href="js/jquery-ui/css/black-tie/jquery-ui-1.8.18.custom.css" />
		<script type="text/javascript" src="js/jquery-ui/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery/plugins/jquery.querystring.js"></script>
		<script type="text/javascript" src="js/jquery/plugins/jquery.listerine.js"></script>
		<link rel="stylesheet" type="text/css" href="js/jquery/plugins/meow/jquery.meow.css" />
		<script type="text/javascript" src="js/jquery/plugins/meow/jquery.meow.js"></script>
	</head>
	<body>
	
		<div id="header">
			<div class="header_wrapper">
				<div class="header_content">
					<p class="header_title">Shibal CMS</p>
				</div>
			</div>
			<div id="header_menu">
				<ul>
					<li><a href="?">home</a></li>
					<?php SiteManager::load_header_menu(); ?>
				</ul>
			</div>
		</div>
		
		<div id="footer">
			<div class="footer_info">
				&copy; <?php echo date('Y');?> . <a href="?">shibalcms</a> . generated in 
				<?php
					$timer->stop();
					echo number_format($timer->duration(), 3) . 's';
				?>
			</div>

		</div>
		
		<div id="content_wrapper clearfix">
			<div id="left_pane">
 				<?php SiteManager::load_widgets(); ?>
			</div>
			<div id="right_pane">
				<div id="content">
 					<?php SiteManager::load_content(); ?>
				</div>
			</div>
		</div>
		
		<div id="master_dialog" style="display: none;"></div>
	</body>
</html>