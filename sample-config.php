<?php
// Report all PHP errors (see changelog)
error_reporting(E_ALL);

// set up site_settings global array
$GLOBALS['site_settings'] = array();
$GLOBALS['site_settings']['site_path'] = '/path/to/shibalcms/'; // must have ending slash
$GLOBALS['site_settings']['site_default_module'] = 'about';
$GLOBALS['site_settings']['db_database'] = 'database name goes here';
$GLOBALS['site_settings']['db_username'] = 'database username goes here';
$GLOBALS['site_settings']['db_password'] = 'database password goes here';
$GLOBALS['site_settings']['site_admin_email'] = 'email@example.com';
$GLOBALS['site_settings']['site_static_salt'] = 'please change this text';
?>