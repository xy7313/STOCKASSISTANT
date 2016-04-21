

<!DOCTYPE html>
<?php
 session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>StockAssistant</title>
<!--		css-->
<!--		<link rel="stylesheet" href="stylesheets/splashscreen.css" type="text/css" />-->
		<link rel="stylesheet" href="stylesheets/bootstrap.min.css">

		<?php include_once 'head.php' ?>
		<script src="js/splashscreen.js"></script>
	</head>
	<body>
		<div id="header">
				<a href="index.php" style="color: #63e3b8; font-size: x-large; font-family: 'Comic Sans MS'"> Stock Assistant</a>
			<div id="menu">
				<?php if(!isset($_SESSION['sess_ID']))
				 echo '<a id="link_login" class="button" href="#">Login</a><a href="register.php" class="button buttonGray">Register</a>';
				 else {
				 	echo "<input type='button' class='welcome' value='Hey there, ".$_SESSION['sess_FirstName']."'</input><a href='./logout.php' class='button buttonGray'>Logout</a>";
				 	echo "<ul class='dropdown'>";
				 	if ($_SESSION['sess_Level'] == 2) 
				 		echo "<li><a href='admin.php'>Administartive Settings</a></li>";
				 	echo "<li><a href='admin.php'>Account Settings</a></li>";
				 	echo "<li><a href='trackedstocks.php'>Tracked Stocks</a></li>";
				 	echo "</ul>";
				 }
				?>
				<div id="login">
						<div id="message_login"></div>
						<label for="email">Email</label>
						<input type="text" id="email" class="input_login"></input>
						<label for="password">Password</label>
						<input type="password" id="password" class="input_login"></input>
				</div>
			</div>
		</div>

		<div id="container" class = "container" >
			<div class = "row">
			<div class = "col-md-10">
				<div id = "left_search" >
					<div id="image" align="center">
						<img src="images/image_search_hover.png" >
					</div>
					<div id="bars_container"></div>
					<div id="search">
						<form action="search.php" method="get">
							<div id="searchbar" >
								<input type="text" value="Search" id="keyword" class="input_search" name="q"/>
							</div>
							<input type="submit" id="button_search" class="button button_search" name="search"/>
							<input type="submit" id="button_suggest" class="button button_search" name="suggest"/>
						</form>
					</div>
				</div>
			</div>
			<div class = "col-md-2">
				<div id = "update">
					<form action="currentCollector.php" >
						<input type="submit" id="button_update_current" class="button button_search" name="update" value="update" />
					</form>
					<form action="updateHistorical.php" method="get">
						<input type="submit" id="button_update_history" class="button button_search" name="daily update" value="daily-up" />
					</form>
					
				</div>
				<table class="table table-striped">
					<caption>list of company</caption>
					<thead>
					<tr>
						<th>company name</th>
						<th>latest price</th>
						<th>last year lowest</th>

					</tr>
					</thead>
					<tbody>
					<tr>
						<td>Tanmay</td>
						<td>Bangalore</td>
						<td>Bangalore</td>

					</tr>
					<tr>
						<td>Sachin</td>
						<td>Mumbai</td>
						<td>Mumbai</td>

					</tr>
					<tr>
						<td>Uma</td>
						<td>Pune</td>
						<td>Mumbai</td>

					</tr>
					</tbody>
				</table>
			</div>
			</div>
		</div>
		<?php include_once('footer.php'); ?>
	</body>
</html>