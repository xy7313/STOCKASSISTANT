<?php

// written by: Robert Adrion
// tested by: Robert Adrion
// debugged by: Robert Adrion

	include_once "dbConnection.php";
	include_once "query.php";

	class controller {
		private $dbConnection; //dbCOnnection object
		private $query; //query object
		
		//constructor creates stockRetriever, stockExtractor, dbCOnnection, and query
		public function __construct() {
			$this->dbConnection = new dbConnection();
			$this->query = new query();
		}
		
		//adds new stock to the database
		public function addStock($post) {
			include_once "stockRetriever.php";
			include_once "stockExtractor.php";
	
			$stockRetriever = new stockRetriever();
			$stockExtractor = new stockExtractor();
			
			$existsList = array(); //existing stocks
			$errorList = array(); //failed stocks
			
			//connect to the database and prepare a statement to get the stockID of a stock based on its ticker
			$this->dbConnection->connect();
			$this->dbConnection->prepare($this->query->get_stockID());

			$stocks = explode(', ',$post); //parse text field into array
			//for each ticker
			foreach($stocks as $ticker) {
				//bind the ticker to the SQL statement and get query results
				$this->dbConnection->bind(1, $ticker);
				$result = $this->dbConnection->single();
				
				//if the stock does not already exist within the database
				if (!$result) {
					
					//retrieve current info
					$document = $stockRetriever->retrieveCurrentInfo($ticker);
					
					//if the stock is legitimate (determined by if the price is not 0.00)
					if (str_getcsv($document)[2] != 0.00) {
						
						//extract current info and get ID of entered stock
						$id = $stockExtractor->extractCurrentInfo($document);
						//retrieve historical prices from 1/1/2004 to current date
						$document = $stockRetriever->retrieveHistorical($ticker, "2012/1/1", date("Y/m/d"));
						//extract historical prices
						$stockExtractor->extractHistorical($document, $id);
					}
					//otherwise add to error list
					else $errorList[] = $ticker;
				}
				//otherwise add to exists list
				else $existsList[] = $ticker;
			}
			
			//UPDATE ALL STOCKS LIST
			
			//get all stocks from the database
			$this->dbConnection->prepare($this->query->get_all_stocks());
			$results = $this->dbConnection->resultSet();
			//disconnect from database
			$this->dbConnection->disconnect();
			
			//create html displaying all stocks
			$table = "<table cellspacing='0'><tr><th>Ticker</th><th>Name</th><th>Exchange</th><th></th></tr>";
			foreach($results as $stock) {
				$table .= "<tr>";
				$table .= "<td>".$stock['Ticker']."</td><td>".$stock['Company']."</td><td>".$stock['Exchange']."</td><td><input type='button' class='remove button' value='X' ticker='".$stock['Ticker']."'/></td>";
				$table .= "</tr>";
			}
			$table .= "</table>";
			
			//return json file containing errors, and all stocks
			$json = array('errors' => $errorList, 'exists' => $existsList, 'table' => $table);
			return json_encode($json, JSON_UNESCAPED_SLASHES);
		}
		
		//remove a stock by ticker
		public function removeStock($ticker) {
			//connect to the databse
			$this->dbConnection->connect();
			//get the current stocks ID from the database
			$this->dbConnection->prepare($this->query->get_stockID());
			$this->dbConnection->bind(1, $ticker);
			$results = $this->dbConnection->single();
			//delet the stock from the stocks table by ID
			$this->dbConnection->prepare($this->query->delete_stock_Stocks());
			$this->dbConnection->bind(1, $results['StockID']);
			$this->dbConnection->execute();
			//delete the stock from the historical prices table by ID
			$this->dbConnection->prepare($this->query->delete_stock_HistoricalPrices());
			$this->dbConnection->bind(1, $results['StockID']);
			$this->dbConnection->execute();
			//update stock list
			$this->dbConnection->prepare($this->query->get_all_stocks());
			$results = $this->dbConnection->resultSet();
			$this->dbConnection->disconnect();
			//create html
			$html = '';
			$html .= "<table cellspacing='0'>";
			$html .= "<tr><th>Ticker</th><th>Name</th><th>Exchange</th><th></th></tr>";
			foreach($results as $stock) {
				$html .= "<tr>";
				$html .= "<td>".$stock['Ticker']."</td><td>".$stock['Company']."</td><td>".$stock['Exchange']."</td><td><input type='button' class='remove button' value='X' ticker='".$stock['Ticker']."'/></td>";
				$html .= "</tr>";
			}
			$html .= "</table>";
			return $html;
		}
		
		public function createPage() {
			include_once 'pagemaker.php';
			include_once 'paginator.php';
			
			//instantiate objects
			$pageMaker = new pageMaker();
			$paginator = new paginator();

					
			//if the suggest button is clicked
			if ($_GET['suggest']) {
					$html = '';
					//search for stocks using keyword					
					$stockIDArray = $paginator->suggest();
					//create results page
					$pageData = $paginator->getPageData();
					$first = $pageData['offset']+1;
					$last = $first+$pageData['itemsPerPage']-1;
					$total = $pageData['totalItems'];
					if($last > $total) $last = $total;
					$html .= "<div id='topmessage'>Showing results ".$first."-".$last." of ".$total."</div>";
					$html .= $pageMaker -> createPage($stockIDArray);
					$html .= $paginator->createPagination();
					return $html;
				}
				//otherwise the search button was clicked 
				else {
					$html = '';
					//get the keyword
					$keyword = $_GET['q'];
					//search for stocks using keyword					
					$stockIDArray = $paginator->search($keyword);
					//if the results exist
					if (count($stockIDArray) > 0) {
						//create results page
						$pageData = $paginator->getPageData();
						$first = $pageData['offset']+1;
						$last = $first+$pageData['itemsPerPage']-1;
						$total = $pageData['totalItems'];
						if($last > $total) $last = $total;
						$html .= "<div id='topmessage'>Showing results ".$first."-".$last." of ".$total."</div>";
						$html .= $pageMaker -> createPage($stockIDArray);
						$html .= $paginator->createPagination();
						return $html;
					} 
					//otherwise display approproate message
					else
						return "<div id='topmessage'>No results found. Please try a different search entry.</div>";
						
					

				}
		}
	}
	
?>