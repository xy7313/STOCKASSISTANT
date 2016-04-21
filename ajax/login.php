<?php

// written by: Robin Karmakar
// tested by: Robin Karmakar
// debugged by: Robin Karmakar

session_start();
include_once '../classes/accountHandler.php';

$accountHandler = new accountHandler(); 
$email = $_POST['email'];
$password = $_POST['password'];

$results = $accountHandler->authenticate($email, $password);
if ($results) {
	session_regenerate_id();
	$_SESSION['sess_ID'] = $results['UserID'];
	$_SESSION['sess_Level'] = $results['Level'];
	$_SESSION['sess_FirstName'] = ucwords($results['FirstName']);
	session_write_close();
	echo 1;
}
else echo 0;
?>