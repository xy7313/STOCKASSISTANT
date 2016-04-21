<!-- 
// written by: Mohammed Latif
// tested by: Mohammed Latif
// debugged by: Mohammed Latif
//-->

<!DOCTYPE html>
 <?php
 session_start();
 if($_SESSION['sess_Level'] != 2)
	header('Location: index.php');
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Stock Hawk: Administration</title>
		<link rel="stylesheet" href="stylesheets/admin.css" type="text/css" />
		<?php include_once 'head.php' ?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript" src="js/ajax_admin.js"></script>
		<script type="text/javascript" src="js/charts_admin.js"></script>

	</head>
	<body>
		<?php include_once("header.php"); ?>
		<div id="container">
			<div id="card_container">
				<div id="stocks" class="card animatedBounceInUp">
						<?php
						include_once "classes/dbConnection.php";
						include_once "classes/query.php";

						//instantiate dbCOnnection and query objects
						$dbConnection = new dbConnection();
						$query = new query();

						//connect to database
						$dbConnection -> connect();
						//get all stocks and their related information
						$dbConnection -> prepare($query -> get_all_stocks());
						$results = $dbConnection -> resultSet();
						$count = $dbConnection -> rowCount();
						?>
						<div class="title">
						All Stocks <?php echo '('.$count.')'; ?>
						</div>
						<div id="stockdata">
						<?php
						//create html table containing said information
						echo "<table cellspacing='0'>";
						echo "<tr><th>Ticker</th><th>Name</th><th>Exchange</th><th></th></tr>";
						foreach ($results as $stock) {
							echo "<tr>";
							echo "<td>" . $stock['Ticker'] . "</td><td>" . $stock['Company'] . "</td><td>" . $stock['Exchange'] . "</td><td><input type='button' class='remove button' value='X' ticker='" . $stock['Ticker'] . "'/></td>";
							echo "</tr>";
						}
						echo "</table>";
						$dbConnection -> disconnect();
						//disconnect from database
						?>
					</div>
				</div>
				<div id="addstock" class="card animatedBounceInUp">
					<div class="title">
						Add Stocks
					</div>
					<textarea id="input_addstock" rows="5" cols="30" name="input_addstock"></textarea>
					<input type="button" class="button button_addstock" value="Add" id="button_addstock"/>
					<div id="message_addstock">
						<p>
							Insert comma seperated ticker symbols of stocks you wish to add into the textfield above and click the "Add" button to add those respective stocks.
						</p>
					</div>
				</div>
				<div id="timers" class="card animatedBounceInUp">
					<div class="title">
						Edit Timers
					</div>
					<span class="timerTitle">Update Timer</span>
					<div class="timercontainer">
						<span class="timerText">Every</span>
						<input type="text" class="timerInput" name="updateTime" id="updateTime" maxlength="2"/>
						<select class="timerSelect" id="updateTimerSelect">
							<option value="Minutes">Minutes</option>
							<option value="Hours">Hours</option>
							<option value="Days">Days</option>
						</select>
						<div class='downarrow'></div>
						<div class="day" id="updateDay">
							<span class="timerText" id="at">at</span>
							<input type="text" class="timerInput ampm" id="updateDayTime" name="updateDayTime" maxlength="4"/>
							<select id="updateAmPm" class="timerSelect">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
							<div class='downarrow'></div>
						</div>
					</div>
					<span class="timerTitle">Prediction Timer</span>
					<div class="timercontainer">
						<span class="timerText">Every</span>
						<input type="text" class="timerInput" name="predictTime" id="predictTime" maxlength="2"/>
						<select class="timerSelect" id="predictTimerSelect">
							<option value="Minutes">Minutes</option>
							<option value="Hours">Hours</option>
							<option value="Days">Days</option>
						</select>
						<div class='downarrow'></div>
						<div class="day" id="predictDay">
							<span class="timerText" id="at">at</span>
							<input type="text" class="timerInput ampm" id="predictDayTime" name="predictDayTime" maxlength="4"/>
							<select id="predictAmPm" class="timerSelect">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
							<div class='downarrow'></div>
						</div>
					</div>
					<div id="message_timer"></div>
					<input type="button" class="button button_timers" value="Update Timers" id="button_timers"/>
				</div>
				<div id="searchAnalytics" class="card animatedBounceInUp">
					<div class="title">
						Search Analytics
					</div>
					<div id="dashboard">
						<div id="graph_search"></div>
						<div id="filter"></div>
					</div>
				</div>
				<div id="predictionAnalytics" class="card animatedBounceInUp">
					<div class="title">
						Prediction Session Durations
					</div>
					<div id="graph_prediction"></div>
				</div>
			</div>
		</div>
		<div id="confirmation">
			<p class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;">
				All data related to this stock, including historical prices as well as user tracks, will be permanently deleted. Are you sure?
			</p>
		</div>
		<?php include_once('footer.php'); ?>
	</body>
</html>