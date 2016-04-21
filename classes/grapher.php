<?php

// written by: Vincent Chen
// tested by: Vincent Chen
// debugged by: Vincent Chen

	include_once "dbConnection.php";
	include_once "query.php";
	
	class grapher {
		private $dbConnection; //dbConnection object
		private $query; //query object
	
	//constructor instantiates dbConnection and query
	public function __construct() {
		$this->dbConnection = new dbConnection();
		$this->query = new query();
	}
	
	//gets historical prices data and returns a json file of that data for an input of a stockID
	public function getGraphData($stockID) {
		//calculate date of 1 year before
		$date = date('Y-m-d'); 
		$date = strtotime('-1 year', strtotime($date)); 
		$date = date('Y-m-d', $date);
		
		//connect to database
		$this->dbConnection->connect();	
		//get historical prices of stock from database for dates greater than one year ago	
		$this->dbConnection->prepare($this->query->get_historical()); 
		$this->dbConnection->bind(1, $stockID);
		$this->dbConnection->bind(2, $date);
		$results = $this->dbConnection->resultSet();
		
		//disconnect from database
		$this->dbConnection->disconnect();
		//return json file of historical prices
		return $results;
		
	}
	
	//gets historical prices data and returns a json file of that data for an input of a stockID
	public function getPredictionData($stockID) {
		//calculate date of 1 year before
		$date = date('Y-m-d'); 
		$date = strtotime('-1 year', strtotime($date)); 
		$date = date('Y-m-d', $date);
		
		//connect to database
		$this->dbConnection->connect();	
		//get predicted prices of stock from database for dates greater than one year ago	
		$this->dbConnection->prepare($this->query->get_predicted_prices()); 
		$this->dbConnection->bind(1, $stockID);
		$this->dbConnection->bind(2, $date);
		$results = $this->dbConnection->resultSet();
		
		//disconnect from database
		$this->dbConnection->disconnect();
		//return json file of historical prices
		return $results;		
	}
	
	//gets keyword counts from the database and returns a json file of that data
	public function getSearchData() {
		//connect to database
		$this->dbConnection->connect();		
		//get keyword data from database
		$this->dbConnection->prepare($this->query->get_keywords());
		$results = $this->dbConnection->resultSet();
		//disconnect from database
		$this->dbConnection->disconnect();
		//return json file of keyword counts
		return $results;
	}
	
	public function getPredictionTimes() {
		//connect to database
		$this->dbConnection->connect();		
		//get keyword data from database
		$this->dbConnection->prepare($this->query->get_predictionTimes());
		$results = $this->dbConnection->resultSet();
		//disconnect from database
		$this->dbConnection->disconnect();
		//return json file of keyword counts
		return $results;
	}
	
	}

?>