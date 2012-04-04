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
					strlen($line) == 0) {
				unset($lines[$linenum]);
			}
		}
		$this->ParsedLines = $lines;
	}
	
	public function Execute()
	{
		$blob = implode($this->ParsedLines);
		$statements = explode(';', $blob);
		foreach ($statements as $sql) {
			echo "$sql<br />\n";
			try {
			DataManager::query($sql);
			} catch (Exception $e) {
				echo $e->getMessage() . "<br />\n";
			}
		}
	}
}
?>