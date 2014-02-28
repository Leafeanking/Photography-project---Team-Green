<?php
include 'functions.php';
session_start();
if(isset($_POST['Logout']) and session_id() != false){
	session_destroy();
}
if(isset($_POST['username']) and isset($_POST['password'])){
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['access'] = authenticate($_POST['username'],$_POST['password']);
}
if(isset($_SESSION['access']) and $_SESSION['access'] != false){
	/*if($_SESSION['access'] == 'admin'){
		header('Location: teacherhome.php');
	}*/
	header('Location: studenthome.php');
}
if(isset($_POST['username']) and isset($_POST['password'])){
	echo "<div id='warning'>Incorrect Username and/or Password</div>";
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
	</head>
	<body>
		<header>
			<!--Icon place holder until we find a icon-->
			<link rel="icon" href="favicon.ico" type="image/x-icon" />
			<!--Logo place holder until we find a logo-->
			<img src="logo.png" alt"Photography Logo" width="313" height="106">
		<h1>Photography</h1>
		</header>
		<div>
		<form action = 'index.php' method = 'POST'>
			<input type='text' name="username" value="Username" />
			<input type='password' name="password" value="Password" />
			<input type='submit' name='Login' />
		</form>
		</div>
	</body>
</html>
