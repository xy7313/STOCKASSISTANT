<?php
	include_once '../classes/controller.php';
	//text field entry
	$post = $_POST['input_addstock'];
	
	if ($post) {
	//instantiate controller
	$controller = new controller();
	//addstock to the database and return necessary data to print as html
	$return = $controller->addStock($post);
	//print html
	echo $return;
	}
?>