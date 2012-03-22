<?php
class SessionManager
{
	public static function start()
	{
		session_start();
	}
	
	public static function stop()
	{
		$_SESSION = array();
		session_destroy();
	}
}
?>