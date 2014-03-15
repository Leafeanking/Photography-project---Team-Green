<?php
session_start();
include_once('functions.php');


if(isset($_POST['Logout']) and session_id() != false){
	session_destroy();
}
if(isset($_POST['username']) and isset($_POST['password'])){
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['access'] = authenticate($_POST['username'],$_POST['password']);
}
if(isset($_SESSION['access']) and $_SESSION['access'] != false){
	if($_SESSION['access'] == 'admin'){
		header('Location: teacherhome.php');
	}
	else{
		header('Location: studenthome.php');
	}
}
if(isset($_POST['username']) and isset($_POST['password'])){
	echo "<div id='warning'>Incorrect Username and/or Password</div>";
}
include_once('javafunctions.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type"text/css" href="photography.css">
		<title>Login</title>
		<!--Icon place holder until we find an super awesome icon-->
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<header id="logo">
		<h1>Photography</h1>
		</header>
		<form action='index.php' method='POST'>
		<div class="menubar">
			<div class="inputBoxs">
			<input type="username" name="username" placeholder="Username"/><br>
			<input type="password" name="password" placeholder="Password"/>
			</div>
			<div class="submitButton">
			<input type="submit" name = "Login">
			</div>
			<p onclick="forgotPassword()">Forgot password</p>
		</form>
		</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
<script>
function forgotPassword()
{
	var email=prompt("Forgot password? Please enter your Dmail user name.");
	var result = send_password(email);
	if(result == 'true')
	{
		var ok=confirm("Your password has been sent to your email.");
		
	}
	else{
		var ok=confirm("Your email does not exist in our system.");
	}
}
</script>
	</body>
</html>
