<?php
	include_once '../classes/grapher.php';
	//instantiate grapher object
	$grapher = new grapher();
	
	//if the search button is clicked
	if ($_POST['stockID']) {
	$stockID = $_POST['stockID'];
	//get historical prices data and return json file of data
	$historical = $grapher->getGraphData($stockID);
	$prediction = $grapher->getPredictionData($stockID);  
	//print json file
	echo json_encode(array('historical'=>$historical,'prediction'=>$prediction));
	}
	
	else {
		//otherwise get search data and prediction timings and return json file of data
		$searchData = $grapher->getSearchData();
		$predictionTimes = $grapher->getPredictionTimes();
		echo json_encode(array('searchData'=>$searchData, 'predictionTimes'=>$predictionTimes)); //print json file
	}
?>