<?php

// written by: Robin Karmakar
// tested by: Robin Karmakar
// debugged by: Robin Karmakar

class editor {
	
	private $file;
	private $updateCurrCron;
	private $updateHistCron;
	private $predictCron;
	
	public function __construct() {
		$this->file = '../DB_maintenance/cron.txt';
		$lines = file($this->file);
		
		$this->updateCurrCron = $lines[0];
		$this->updateHistCron = $lines[1];
		$this->predictCron = $lines[2];
	}
	//read cron file for update time
	public function getUpdateTime() {
		
		$pos = strpos($this->updateCurrCron, ' * * /');
		$cron = substr($this->updateCurrCron,0,$pos);
		$cron = str_replace('*/','',$cron);
		return explode(' ',$cron);
	}
	//read cron file for predict time
	public function getPredictTime() {
		
		$pos = strpos($this->predictCron, ' * 0-');
		$cron = substr($this->predictCron,0,$pos);
		$cron = str_replace('*/','',$cron);
		return explode(' ',$cron);
	}
	//set times based on input
	public function setTime($updateMin = NULL, $updateHour = NULL, $updateDay = NULL, $predictMin = NULL, $predictHour = NULL, $predictDay = NULL) {
		if ($updateDay) {
			$updateDay = '*/'.$updateDay;
		}
		else if ($updateHour || $updateMin) {
			$updateDay = '*';
			
			if ($updateHour) $updateHour = '*/'.$updateHour;
			else $updateHour = '*';
			
			if ($updateMin) $updateMin = '*/'.$updateMin;
			else $updateMin = '*';
		}
		else {
			$updateDay = '*';
			$updateHour = '*';
			$updateMin = '*/15';
		}
		
		if ($predictDay) {
			$predictDay = '*/'.$predictDay;
		}
		else if ($predictHour || $predictMin){
			$predictDay = '*';
			
			if ($predictHour) $predictHour = '*/'.$predictHour;
			else $predictHour = '*';
			
			if ($predictMin) $predictMin = '*/'.$predictMin;
			else $predictMin = '*';
		}
		else {
			$predictDay = '*/1';
			$predictHour = 17;
			$predictMin = 0;
		}
		
		$pos = strpos($this->updateCurrCron, ' * * /');
		$updateEnd = substr($this->updateCurrCron, $pos);
		
		$pos = strpos($this->predictCron, ' * 0-4');
		$predictEnd = substr($this->predictCron, $pos);
	
		$cron = $updateMin.' '.$updateHour.' '.$updateDay.$updateEnd.$this->updateHistCron.$predictMin.' '.$predictHour.' '.$predictDay.$predictEnd;
				
		file_put_contents($this->file, $cron);
	}
}

?>