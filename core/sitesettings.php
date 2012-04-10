<?php

class SiteSettings
{
	public static function get($key)
	{
		// check if key exists in config file
		if (array_key_exists($key, $GLOBALS['site_settings']))
		{
			return $GLOBALS['site_settings'][$key];
		}
		else
		{
			// check if key exists in database
			$sql = sprintf('select * from sitesetting where name = \'%s\'', DataManager::sanitize($key));
			$result = DataManager::query($sql);
			if ($result)
			{
				$row = DataManager::fetch_array($result);
				return $row[$key];
			}
		}
		// nothing found
		return null;
	}
	
	public static function set($key, $value)
	{
		if (!array_key_exists($key, $GLOBALS['site_settings']))
			{
			// check to see if key exists
			$sql = sprintf('select * from sitesetting where name = \'%s\'', DataManager::sanitize($key));
			$result = DataManager::query($sql);
			if ($result)
			{
				// key found, update
				$sql = sprintf('update sitesetting set value = \'%s\' where name = \'%s\'', 
								DataManager::sanitize($value), DataManager::sanitize($key));
				DataManager::query($sql);
				return true;
			}
			else
			{
				// key not found, insert.
				$sql = sprintf('insert into sitesetting (name, value) values (\'%s\', \'%s\')', 
								DataManager::sanitize($value), DataManager::sanitize($key));
				DataManager::query($sql);
				return true;
			}
		}
		return false;
	}
}