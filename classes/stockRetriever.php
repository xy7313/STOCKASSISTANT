<?php

// written by: Robert Adrion
// tested by: Robert Adrion
// debugged by: Robert Adrion

	class stockRetriever {
		//request URL for historical prices
		private $requestURL_historical = "http://ichart.yahoo.com/table.csv?s=";
		//request URL for current information
		private $requestURL_current = "http://download.finance.yahoo.com/d/quotes.csv?s=";
		//request URL for decsription
		private $requestURL_desc = "https://www.google.com/finance?q=";

		//get file from outside URL
		public function get_content($URL) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $URL);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		//retrieve historical prices for a $ticker from $startDate to $endDate and return CSV file
		public function retrieveHistorical($ticker, $startDate, $endDate) {
			//parse start date
			$startDate_ = explode("/", $startDate);
			//parse end date
			$endDate_ = explode("/", $endDate);
			//create URL
			$URL = $this->requestURL_historical.$ticker."&a=".($startDate_[1] - 1)."&b=".$startDate_[2]."&c=".$startDate_[0]."&d=".($endDate_[1] - 1)."&e=".$endDate_[2]."&f=".$endDate_[0];
			//return CSV file
			return $this->get_content($URL);
			
		}
		
		//retrieve current stock information for $ticker
		public function retrieveCurrentInfo($ticker) {
			//create URL
			$URL = $this->requestURL_current.$ticker."&f=snl1x";
			//get CSV file
			$csv = $this->get_content($URL);
			$exchange = str_getcsv($csv)[3];
			
			$URL = $this->requestURL_desc.$exchange."%3A".$ticker;
			$content = $this->get_content($URL);
			
			$dom = new DomDocument;

			// We need to validate our document before refering to the id
			$dom->validateOnParse = true;
			$dom->loadHTML($content);
			$xpath = new DOMXPath($dom);
			
			$divContent = $xpath->query('//div[@class="companySummary"][1]')->item(0);
			$desc = $divContent->nodeValue;
			$desc = str_replace('More from Reuters', '', $desc);
			$desc = preg_replace('/[^A-Za-z0-9\. -]/', '', $desc);
			
			return array('csv'=>$csv,'desc'=>$desc);
			
			
		}
		
		//retrieve current price
		public function retrieveCurrentPrice($ticker) {
			//create URL
			$URL = $this->requestURL_current.$ticker."&f=l1";
			//return CSV file
			return $this->get_content($URL);
		}
		
		
	}
?>