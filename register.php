<!--
// written by: Syedur Rahma
// tested by: Syedur Rahma
// debugged by: Syedur Rahman
//-->

<!DOCTYPE html>
 <?php
 session_start();
 if(isset($_SESSION['sess_ID']))
	header('Location: index.php');
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Stock Hawk: Administration</title>
		<link rel="stylesheet" href="stylesheets/register.css" type="text/css" />
		<?php include_once 'head.php' ?>
		<script type="text/javascript" src="js/register.js"></script>
	</head>
	<body>
		<?php include_once("header.php"); ?>
		<div id="container">
		<div class="card animatedBounceInUp" id="registerContainer">
		<div class="title">Registration</div>
		<div class="required">(*) Required</div>

			<form>
			<div id="firstName" class="rowContainer">
				<label for="firstName">First Name*</label>
				<input type="text" id="input_firstName"></input>
				<div class="message"></div>
			</div>
			<div id="lastName" class="rowContainer">
				<label for="lastName">Last Name*</label>
				<input type="text" id="input_lastName"></input>
				<div class="message"></div>
			</div>
			<div id="emailReg" class="rowContainer" style="clear: both">
				<label for="emailReg">Email*</label>
				<input type="text" id="input_emailReg"></input>
				<div class="message"></div>
			</div>
			<div id="password1" class="rowContainer" style="clear: both">
				<label for="password1">Password*</label>
				<input type="password" id="input_password1"></input>
				<div class="message"></div>
			</div>
			<div id="password2" class="rowContainer">
				<label for="password2">Comfirm Password*</label>
				<input type="password" id="input_password2"></input>
				<div class="message"></div>
			</div>
			<div id="phone" class="rowContainer" style="clear: both">
				<label for="phone">Mobile Number</label>
				<input type="text" id="input_phone"></input>
				<div class="message"></div>
			</div>
			<div id="carrier" class="rowContainer">
				<label for="carrier">Carrier</label>
				<select id="select_carrier">
					<option></option>
					<option>ATT</option>
					<option>Sprint</option>
					<option>TMboile</option>
					<option>Verizon</option>
				</select>
				<div class='downarrow'></div>
				<div class="message"></div>
			</div>
				<input type="button" class="button" id="registerSubmit" value="Register"></submit>
			</form>
			</div>
		</div>
		<?php include_once('footer.php'); ?>
	</body>
</html>