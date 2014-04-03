<?php
	include_once("secure.php");
	include_once("functions.php");
	$result = dbGet("select * from users where email = '$_SESSION[username]'");
	$data = mysql_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type"text/css" href="photography.css">
		<title>Updating Profile</title>
		<!--Icon place holder until we find an super awesome icon-->
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<!--DON'T NEED THIS JAVASCRIPT LINE: <script src="photography.js"></script>-->
	</head>
	<body>
		<header id="logo">
		</header>
		<div class="redline links">
		<ul>
	
			<li class="left avatar"> <a> <div id='avatar'><img src="avatar.php?email=<?php echo $_SESSION['username']?>" alt="avatar" title="Change Picture"></div></a> </li>
			<li class="left"> <a href="index.php">Back</a> </li>
			<li class="right "> <form action='index.php' method='POST'>
					<input type='submit' name = 'Logout' value='Logout'>
					</form>
			</li>
			</ul>
		</div>
		<div class="menubar">
			<form action='index.php' method='POST'>
				<div class="newUser_inputBoxes">
					<br>
					
					<label>Name:</label><input type="text" name="name" value="<?php echo $data['username'];?>"><br>
					<label>Email:</label> <input type="email" name="email" value="<?php echo $data['email'];?>"><br>
					<label>New password:</label><input type="password" name="passwordNew1" value=""><br>
					<label>Re-enter new password:</label><input type="password" name="passwordNew2" value=""><br>
					<div id='choose_class_to_join'>
						<?php
							if($_SESSION['access'] != 'admin'){
								echo '<h3>Check box to belong to class.</h3>';
								echo '<h5>Having no classes will delete your user</h5>';
								$query = "select class from classes";
								$results = dbGet($query);
								while($class = mysql_fetch_assoc($results)){
									if(in_array($class['class'],$data)){
										echo "<label>$class[class]</label><input type='checkbox' name='class[]' value='$class[class]' checked><br><hr/>";
									}
									else{
										echo "<label>$class[class]</label><input type='checkbox' name='class[]' value='$class[class]'><br><hr/>";
									}
								}
							}
						?>
					</div>
				</div>
				<div>
					<br><br>
					<h3>Current password must be entered for any changes</h3>
					<div class="newUser_inputBoxes">
					<label>Current Password:</label><input type="password" name="passwordOld" value=""><br>
					<input class="saveButton" type="submit" name="update_user_info" value="Save">
					</div>
				</div>
			</form>
		</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
	</body>
</html>

