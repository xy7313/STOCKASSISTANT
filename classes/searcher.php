<?php

// written by: Vincent Chen
// tested by: Vincent Chen
// debugged by: Vincent Chen

include_once 'dbConnection.php';
include_once 'query.php';

class searcher {
	
	private $dbConnection; //dbConnection object
	private $query; //query object
	
	//constructer instantiates dbConnection and query
	public function __construct() {
		$this->dbConnection = new dbConnection();
		$this->query = new query();
	}
	
	//search for stocks using a keyword and return an array of matching stocks' IDs
	public function getSearchCount($keyword) {
		//connect to database
		$this->dbConnection->connect();
		
		//LOGGING THE KEYWORD
		
		//check if keyword exists by attempting to get its search count from the database
		$this->dbConnection->prepare($this->query->get_keyword_count());
		//bind the keyword to the SQL statement
		$this->dbConnection->bind(1, $keyword);
		//get the query results	
		$count = $this->dbConnection->singleData();
		
		//if the count exists then the keyword exists
		if ($count) {
			$count++; //increment the count
			//save the new count to the database for the given keyword
			$this->dbConnection->prepare($this->query->update_keyword());
			$this->dbConnection->bind(1, $count);
			$this->dbConnection->bind(2, $keyword);
			$this->dbConnection->execute();
		}
		else {
			//otherwise the keyword does not exist, insert it into the database with a default value of 1
			$this->dbConnection->prepare($this->query->insert_keyword());
			$this->dbConnection->bind(1, $keyword);
			$this->dbConnection->execute();
		}//END LOGGING KEYWORD
		
		//BEGIN SEARCH
		//prepare the search SQL statement
		$this->dbConnection->prepare($this->query->getSearchCount());
		//bind keyword to statement (twice for searching both ticker and company name)
		$this->dbConnection->bind(1, '%'.$keyword.'%');
		$this->dbConnection->bind(2, '%'.$keyword.'%');
		//return results and disconnect from database
		$count = $this->dbConnection->singleData();
		$this->dbConnection->disconnect();
		
		return $count; //return array of stockIDs
	}
	
	public function search($keyword, $offset, $itemsPerPage) {
		$this->dbConnection->connect();
		$this->dbConnection->prepare($this->query->search());
		//bind keyword to statement (twice for searching both ticker and company name)
		$this->dbConnection->bind(1, '%'.$keyword.'%');
		$this->dbConnection->bind(2, '%'.$keyword.'%');
		$this->dbConnection->bind(3, $offset);
		$this->dbConnection->bind(4, $itemsPerPage);
		//return results and disconnect from database
		
		$results = $this->dbConnection->resultSet();
		$this->dbConnection->disconnect();
		
		$stockIDs = array(); //create new array
		
		//for each result add the stockID of the stock to the array
		foreach($results as $stock) {
			$stockIDs[] = $stock[StockID];
		}
		return $stockIDs; //return array of stockIDs
	}
	
	//returns an array of stocks with the highest predicted gain
	public function suggest($offset, $itemsPerPage) {
		//connect to the database
		$this->dbConnection->connect();
		//prepare SQL statement
		$this->dbConnection->prepare($this->query->getSuggestedStocks());
		$this->dbConnection->bind(1, $offset);
		$this->dbConnection->bind(2, $itemsPerPage);
		//get results and disconnect from the databse
		$results = $this->dbConnection->resultSet();
		$this->dbConnection->disconnect();
		
		$stockIDs = array(); //create new array
		
		//for each result add the stockID of the stock to the array
		foreach($results as $stock) {
			$stockIDs[] = $stock[StockID];
		}
		return $stockIDs; //return array of stockIDs
	}
	
}

?>
