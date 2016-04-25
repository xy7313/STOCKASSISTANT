<?php

// written by: Mohammed Latif
// tested by: Mohammed Latif
// debugged by: Mohammed Latif

include_once 'dbConnection.php';
include_once 'query.php';

class pageMaker {
	private $dbConnection; //dbconnection object
	private $query; //query object	

	//constructer instantiates dbConnection and query
	public function __construct() {
		$this -> dbConnection = new dbConnection();
		$this -> query = new query();
	}

	//creates the search results page and returns html code where stockIDArray is an array of stockIDs to be displayed to the user
	public function createPage($stockIDArray) {
		date_default_timezone_set('America/New_York'); //set time zone for displaying times
		$html = ''; //html to be returned.
		//connect to database
		$this -> dbConnection -> connect();

		if (isset($_SESSION['sess_ID'])) {
			$this -> dbConnection -> prepare($this->query->getAllTrackedStocks());
			$this -> dbConnection -> bind(1, $_SESSION['sess_ID']);
			$trackedStocks = $this->dbConnection->resultSet();
		}
		
		//for each stockID within the array
		foreach ($stockIDArray as $stockID) {
			$tracked = false;
			foreach ($trackedStocks as $stock) {
				if ($stock['StockID'] == $stockID) {
					$tracked = true;
					break;
				}
			}
			
			//get all the stock information and store it in results
			$this -> dbConnection -> prepare($this -> query -> get_stock());
			$this -> dbConnection -> bind(1, $stockID);
			$result = $this -> dbConnection -> single();

			//create html
			$html .= "<div class='flipContainer stockFlipperContainer animatedBounceInUp'><div class='flipper'><div class='card cardFront'><div class='cardinfo'><span class='title'>";
			$html .= $result['Company'];
			$html .= "</span>";
			if (isset($_SESSION['sess_ID']) && !$tracked)
				$html .= "<a class='trackLink track' stockID='".$stockID."' alt='Track' href='#'>Track</a>";
			else
				$html .= "<a class='trackedLink track' stockID='".$stockID."' alt='Remove from Tracked List' href='#'>Track</a>";
			$html .= "<div class='info'>";
			$html .= $result['Exchange'].": ".$result['Ticker']." | Last Updated: ".date('m/d/Y h:i A', $result['Time']);
			$html .= "</div><div class='currentPrice'><p>Current Price</p><p class='price'>";
			$html .= $result['Price'];
			$html .= "</p></div></div><div class='chart_new noClick' style='width: 600px; height: 250px;' id='";
			$html .= $stockID;
			$html .= "'></div></div><div class='card cardBack'><div class='title'>";
			$html .= $result['Company'];
			$html .= "</div>";
			$html .= $result['Description'];
			$html .= "</div></div></div>";
			
			$this->dbConnection->prepare($this -> query -> get_predictionData());
			$this -> dbConnection -> bind(1, $stockID);
			$Predictionresult = $this -> dbConnection -> single();
			
			$html .= "<div class='card prediction animatedBounceInRight'><div class='predictedprice'>Predicted Five Day Avg: ";
			$html .= $Predictionresult['AvgPrice'];
			$html .= "</div><div class='tomorrow'>Predicted Next Day's Closing: ";
			$html .= $Predictionresult['NextDayPrice'];

			//add two more query:
			//2) Get the highest stock price of Google in the last ten days
			//3) Average stock price of Microsoft in the latest one year
			$html .= "</div><div class='tomorrow'>Predicted Next Day's Closing: ";
			$html .= $Predictionresult['NextDayPrice'];
			$html .= "</div><div class='tomorrow'>Predicted Next Day's Closing: ";
			$html .= $Predictionresult['NextDayPrice'];

			$html .= "</div><div class='confidence'>Confidence: ";
			$html .= $Predictionresult['ConfidenceValue'];
			$html .= "%</div><div class='PredictedDecision ".$Predictionresult['PredictedDecision']."'>";
			$html .= $Predictionresult['PredictedDecision'];
			$html .= "</div><div class='waitTime'>in ";
			$html .= $Predictionresult['WaitTime'];
			$html .= " days for maximum predicted profit</div><div class='predictdate'>Predicted on ";
			$html .= date('m/d/Y', strtotime($Predictionresult['Date']));
			$html .= "</div></div>";
			
			$url = str_replace(' ', '%20', $result['Company']);
			
			$html .= "<div class='card share animatedBounceInRight'>";
			$html .= "<a href='https://www.facebook.com/sharer/sharer.php?u=http://localhost:8888/1_code/search.php?q=";
			$html .= $url;
			$html .= "' target='_blank'><div class='shareLink' id='facebook'></div></a>";
			$html .= "<a href='https://twitter.com/home?status=Check%20this%20awesome%20stock%20out!%20http://localhost:8888/1_code/search.php?q=";
			$html .= $url;
			$html .= "' target='_blank'><div class='shareLink' id='twitter'></div></a>";
			$html .= "<a href='https://plus.google.com/share?url=http://localhost:8888/1_code/search.php?q=";
			$html .= $url;
			$html .= "' target='_blank'><div class='shareLink' id='gplus'></div></a>";
			$html .= "<a href='https://www.linkedin.com/shareArticle?mini=true&title=Stock%20Prediction&url=http://localhost:8888/1_code/search.php?q=";
			$html .= $url;
			$html .= "' target='_blank'><div class='shareLink' id='linkedin'></div></a>";
			$html .= "</div>";
		}
		$this -> dbConnection -> disconnect(); //disconnect from database;
		return $html; //return html
	}

}
?>