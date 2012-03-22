<?php
class DataManager
{
	private static function connect()
	{
		try {		
			$conn = mysql_connect('localhost', $GLOBALS['site_settings']['db_username'], $GLOBALS['site_settings']['db_password']);
			mysql_select_db($GLOBALS['site_settings']['db_database'], $conn);
		} catch (Exception $e) {
			echo 'ERROR: could not connect to database.<br />' . mysql_error(DataManager::connect());
		}
		return $conn;
	}
	
	public static function query($sql)
	{
		try {
			$result = mysql_query($sql, DataManager::connect());
		} catch (Exception $e) {
			echo 'ERROR: query failed.<br />' . mysql_error(DataManager::connect());
		}
		return $result;
	}
	
	public static function fetch_array($resource)
	{
		try {
			return mysql_fetch_array($resource);
		} catch (Exception $e) {
			echo 'ERROR: could not fetch row.<br />' . mysql_error(DataManager::connect());
		}
	}
	public static function sanitize($input)
	{
		try {
			return mysql_real_escape_string($input, DataManager::connect());
		} catch (Exception $e) {
			echo 'ERROR: could not sanitize.<br />' . mysql_error(DataManager::connect());
		}
	}
	
	public static function last_insert_id()
	{
		try {
			return mysql_insert_id(DataManager::connect());
		} catch (Exception $e) {
			echo 'ERROR: could not retrieve last insert id.<br />' . mysql_error(DataManager::connect());
		}
	}
	
	public static function num_rows($result)
	{
		try {
			return mysql_num_rows($result);
		} catch (Exception $e) {
			echo 'ERROR: could not retrive number of rows in result<br />' . mysql_error(DataManager::connect());
		}
	}
}
?>