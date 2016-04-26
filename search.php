<!--
// written by: Peter Zhang
// tested by: Peter Zhang
// debugged by: Peter Zhang
//--> 

<!DOCTYPE html>
<?php
 session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>StockAssistant Search</title>
		<link rel="stylesheet" href="stylesheets/search.css" type="text/css" />
		<?php include_once 'head.php' ?> 
		<script type="text/javascript" src='https://www.google.com/jsapi?autoload={"modules":[{"name":"visualization","version":"1","packages":["annotationchart"]}]}'></script>
		<script type="text/javascript" src="js/charts_search.js"></script>
	</head>
	<body>
		<?php include_once("header.php"); ?>
		<div id="container">
				<?php
				include_once('classes/controller.php');
				$controller = new controller;
				echo $controller->createPage();
				
				?>
		</div>
			<?php include_once('footer.php'); ?>
	</body>
</html>

