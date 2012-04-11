<?php
require_once 'datamanager.php';

class SqlParser
{
	public $ParsedLines;
	public $File;
	
	public function __construct($filepath)
	{
		$this->ParsedLines = array();
		$this->File = $filepath;
	}
	
	public function Parse()
	{
		// strip comments
		$lines = file($this->File);
		foreach ($lines as $linenum => $line) {
			if (substr($line, 0, 2) == '--' || 
					substr($line, 0, 1) == '#' ||
					empty($line)) {
				unset($lines[$linenum]);
			}
		}
		$this->ParsedLines = $lines;
	}
	
	public function Execute()
	{
		$blob = implode($this->ParsedLines);
		$blob = str_replace('{{db_database}}', SiteSettings::get('db_database'), $blob);
		$statements = explode(';', $blob);
		foreach ($statements as $sql) {
			try {
				DataManager::query($sql);
			} catch (Exception $e) {
				echo $e->getMessage() . "<br />\n";
			}
		}
	}
}