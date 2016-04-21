<!DOCTYPE html>
<!--
// written by: Syedur Rahman
// tested by: Syedur Rahman
// debugged by: Syedur Rahman
//-->


<?php
 session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Stock Hawk: Search</title>
		<link rel="stylesheet" href="stylesheets/search.css" type="text/css" />
		<?php include_once 'head.php' ?> 
		<script type="text/javascript" src='https://www.google.com/jsapi?autoload={"modules":[{"name":"visualization","version":"1","packages":["annotationchart"]}]}'></script>
		<script type="text/javascript" src="js/charts_search.js"></script>
	</head>
	<body>
		<?php include_once("header.php"); ?>
		<div id="container">
				<?php
				include_once('classes/dbConnection.php');
				include_once('classes/query.php');
				include_once('classes/pageMaker.php');
				
				$dbConnection = new dbConnection();
				$query = new query();
				$pageMaker = new pageMaker();
				
				$dbConnection->connect();
				$dbConnection->prepare($query->getAllTrackedStocks());
				$dbConnection->bind(1, $_SESSION['sess_ID']);
				$results = $dbConnection->resultSet();
				$dbConnection->disconnect();
				$stockIDs = array(); //create new array
		
				//for each result add the stockID of the stock to the array
				foreach($results as $stock) {
					$stockIDs[] = $stock[StockID];
				}
	
				echo $pageMaker -> createPage($stockIDs);
				
				?>
		</div>
			<?php include_once('footer.php'); ?>
	</body>
</html>
