<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type"text/css" href="photography.css">
		<title>Create New User Account</title>
		<!--Icon place holder until we find an super awesome icon-->
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<!--DON'T NEED THIS JAVASCRIPT LINE: <script src="photography.js"></script>-->
	</head>
	<body>
		<header id="logo">
		<h1>Create New User Account</h1>
		</header>
		<div class="menubar">
		<?php
		//Fail messages in case creation of user account has problems.
		if(isset($_GET['fail'])){
			$errors = array(
				"NO CLASS CHOSEN",
				"PASSWORDS DO NOT MATCH",
				"A FIELD WAS LEFT EMPTY",
				"EMAIL ALREADY USED"				
				);
			$fail =$errors[$_GET['fail']];
			echo "<h2>Create User Failed:</h2>";
			echo "<h3>$fail</h3>";
		}
		?>
		<form action='index.php' method='POST'>
			<div class="newUser_inputBoxes">
				<br>
				<label>First name:</label><input type="text" name="FirstName" value=""><br>
				<label>Last name:</label><input  type="text" name="LastName" value=""><br>
				<label>Email:</label><input type="email" name="username" value=""><br>
				<label>Password:</label><input type="password" name="password" value=""><br>
				<label>Re-enter password:</label><input type="password" name="passwordconfirm" value=""><br>
			</div>
			<div id='choose_class_to_join'>
			<h3>Choose one or more classes</h3>
			<?php
				include_once("functions.php");
				$query = "select class from classes";
				$results = dbGet($query);
				while($class = mysql_fetch_assoc($results)){
					echo "<label>$class[class]</label><input type='checkbox' name='class[]' value='$class[class]'><hr/>";
				}
			?>
			</div>
			<br>
			<input class="submitButton" type="submit" name="submit_new_user" value="Create Account">
		</form>
		</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
	</body>
</html>

