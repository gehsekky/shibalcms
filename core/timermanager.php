<?php
class TimerManager
{
	private $start_time;
	private $stop_time;
	
	public function TimerManager()
	{
		$this->start_time = NULL;
		$this->stop_time = NULL;
	}
	
	public function start()
	{
		$temp = microtime();
		$temp = explode(' ', $temp);
		$this->start_time = $temp[1] + $temp[0];		
	}
	
	public function stop()
	{
		$temp = microtime();
		$temp = explode(' ', $temp);
		$this->stop_time = $temp[1] + $temp[0];
	}
	
	public function duration()
	{
		return $this->stop_time - $this->start_time;
	}
}