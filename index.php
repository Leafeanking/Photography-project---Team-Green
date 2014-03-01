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
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type"text/css" href="photography.css">
		<title>Login</title>
		<!--Icon place holder until we find a icon-->
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<header id="logo">
		<h1>Photography</h1>
		</header>
		<div id="menubar">
			<div id="inputBoxs">
			<form action='index.php' method='POST'>
			<input type="username" name="username" placeholder="Username"/><br>
			<input type="password" name="password" placeholder="Password"/>
			<input type='submit' name = 'Login'>
		</form>
		</div>
	</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
	</body>
</html>
