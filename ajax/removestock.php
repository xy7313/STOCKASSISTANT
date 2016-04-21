<?php
	include_once '../classes/controller.php';
	//ticker symbol of stock to be removed
	$post = $_POST['ticker'];
	//instantiate controller
	$controller = new controller();
	//remove stock and return data to be printed as html
	$return = $controller->removeStock($post);
	echo $return; //print html
	?>