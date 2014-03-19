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
		<header>
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
				
				First name: <input type="text" name="FirstName" value=""><br>
				Last name: <input  type="text" name="LastName" value=""><br>
				Username/Email: <input type="email" name="username" value=""><br>
				Password: <input type="password" name="password" value=""><br>
				Re-enter password: <input type="password" name="passwordconfirm" value=""><br>
				Choose class: <select name='class'>
				<option value="none">Choose</option>
					<?php
						include_once("functions.php");
						$query = "select class from classes";
						$results = dbGet($query);
						while($class = mysql_fetch_assoc($results)){
							echo "<option value='$class[class]'>$class[class]</option>";
						}
					?>
				</select>
			</div>
			<div class="submitButton">
			<input type="submit" name="submit_new_user">
			</div>
		</form>
		</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
	</body>
</html>

