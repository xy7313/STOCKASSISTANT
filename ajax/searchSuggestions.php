<?php

include_once '../classes/dbConnection.php';
include_once '../classes/query.php';

$dbConnection = new dbConnection(); //dbConnection object
$query = new query(); //query objct

//connect to database
$dbConnection->connect();
//get all ticker symbols and company names from database and disconnect
$dbConnection->prepare($query->get_all_tickers_and_companies());
$results = $dbConnection->resultSet();
$dbConnection->disconnect();

$suggestions = array(); //new array of suggestions

//add ticker and company for each stock to the array
foreach($results as $stock) {
	$suggestions[] = $stock['Ticker'];
	$suggestions[] = $stock['Company'];
}

echo json_encode($suggestions); //return array converted to json file

?>