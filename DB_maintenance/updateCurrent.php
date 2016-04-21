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
	
	//conect to database
	$dbConnection->connect();
	//get stockID and ticker of all stocks
	$dbConnection->prepare($query->get_stockID_and_ticker());
	$results = $dbConnection->resultset();
	//disconnect from database
	$dbConnection->disconnect();
	//for each stocks
	foreach ($results as $stock) {
		//retrieve current price
		$document = $stockRetriever->retrieveCurrentPrice($stock['Ticker']);
		//extract current price
		$stockExtractor->extractCurrentPrice($document, $stock['StockID']);
	}
?>