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
					<form>
						<div id="message_login"></div>
						<label for="email">Email</label>
						<input type="text" id="email" class="input_login"></input>
						<label for="password">Password</label>
						<input type="password" id="password" class="input_login"></input>
					</form>
				</div>
			</div>
			<div id="bars_container"></div>
			<div id="search">
				<form action="search.php" method="get">
					<div id="searchbar">
						<input type="text" value="Search" id="keyword" class="input_search" name="q"/>
					</div>
					<input type="submit" id="button_search" class="button button_search" name="search"/>
					<input type="submit" id="button_suggest" class="button button_search" name="suggest"/>
				</form>
			</div>
			</div>
