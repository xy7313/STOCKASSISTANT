<?php
	include_once '../classes/dbConnection.php';
	include_once '../classes/query.php';
	include_once '../classes/stockRetriever.php';
	include_once '../classes/stockExtractor.php';

	//instantiate necessary objects
	$dbConnection = new dbConnection();
	$query = new query();
	$stockRetriever = new stockRetriever();
	$stockExtractor = new stockExtractor();
	
	//connect to database
	$dbConnection->connect();
	//get last date of historical prices for every stock
	$dbConnection->prepare($query->get_last_date());
	$results = $dbConnection->resultSet();
	$update = false;
	//for each stock
	foreach ($results as $stock) {
		//get ticker symbol
		$dbConnection->prepare($query->get_ticker());
		$dbConnection->bind(1, $stock['StockID']);
		$ticker = $dbConnection->singleData();
		//if the most recent date does not equal todays date
		if ($stock['recentDate'] != date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))))) {
			//startdate is most recent date + 1
			$startDate = strtotime("+1 day", strtotime($stock['recentDate']));
			$startDate = date("Y/m/d", $startDate);
			//retrieve historical prices
			$document = $stockRetriever->retrieveHistorical($ticker, $startDate, date('Y/m/d'));
			//extract historical prices
			$test = $document[0].$document[1].$document[2].$document[3];
			if ($test == 'Date') {
				if (!$update) $update = true;
				$stockExtractor->extractHistorical($document, $stock['StockID']);
				echo "Historical prices for Stock ".$stock['StockID'].": ".$ticker." updated!</br>";
			}
		}
	}
	if (!$update) echo "No new data found.";
	//disconnect from database
	$dbConnection->disconnect();
?>