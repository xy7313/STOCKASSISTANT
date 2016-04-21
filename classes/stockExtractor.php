<?php

// written by: Mohammed Latif
// tested by: Mohammed Latif
// debugged by: Mohammed Latif

	include_once "dbConnection.php";
	include_once "query.php";
	
	class stockExtractor {
		private $dbConnection; //dbConnection object
		private $query; //query object
		
		//constructor creates dbConnection and query object
		public function __construct() {
			$this->dbConnection = new dbConnection();
			$this->query = new query();
		}
		
		//extract historical prices where $document is a CSV file of historical prices and $stockID is the ID for the given stock
		public function extractHistorical($document, $stockID) {
			
			//used to ignore first line of CSV file
			$isFirst = true;
			//parse CSV file into lines
			$sourceLines = str_getcsv($document, "\n");
			//connect to database
			$this->dbConnection->connect();
			//prepare insertion statement
			$this->dbConnection->prepare($this->query->insert_historical());
			//for each line
			foreach($sourceLines as $line) {
				//parse contents of each line into an array
				$contents = str_getcsv( $line );
				
				//skip first line
				if ($isFirst) {
					$isFirst = false;
					continue;
				}
				
				//bind necessary values to SQL statement
				$this->dbConnection->bind(1, $stockID);
				$this->dbConnection->bind(2, $contents[0]);
				$this->dbConnection->bind(3, $contents[1]);
				$this->dbConnection->bind(4, $contents[2]);
				$this->dbConnection->bind(5, $contents[3]);
				$this->dbConnection->bind(6, $contents[4]);
				$this->dbConnection->bind(7, $contents[5]);
				$this->dbConnection->bind(8, $contents[6]);
				
				//execute query
				$this->dbConnection->execute();
			}
			//disconnect from databases
			$this->dbConnection->disconnect();
		}
		
		//extract current information where $document is the retrieved CSV file
		public function extractCurrentInfo($document) {
			date_default_timezone_set('America/New_York');
			//pase CSV file into array
			$contents = str_getcsv($document['csv']);
			$desc = $document['desc'];
			//connect to database
			$this->dbConnection->connect();
			//prepare statement
			$this->dbConnection->prepare($this->query->insert_newStock());
			//bind values to SQL statement
			$this->dbConnection->bind(1, $contents[0]);
			$this->dbConnection->bind(2, $contents[1]);
			$this->dbConnection->bind(3, $contents[2]);
			$this->dbConnection->bind(4, $contents[3]);
			$this->dbConnection->bind(5, $desc);
			$this->dbConnection->bind(6, time());
			//execute query
			$this->dbConnection->execute();
			//get ID of inserted stock
			$id = $this->dbConnection->lastInsertId();
			//disconnect from database
			$this->dbConnection->disconnect();
			//return id
			return $id;
		}
		
		public function extractCurrentPrice($document, $stockID) {
			$contents = str_getcsv($document);
			//connect to database
			$this->dbConnection->connect();
			//prepare statement
			$this->dbConnection->prepare($this->query->update_price());
			//bind values to SQL statement
			$this->dbConnection->bind(1, $contents[0]);
			$this->dbConnection->bind(2, time());
			$this->dbConnection->bind(3, $stockID);
			//execute query
			$this->dbConnection->execute();
			//disconnect from database
			$this->dbConnection->disconnect();
		}
	}
	
	?>