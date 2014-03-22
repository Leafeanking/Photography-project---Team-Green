<?php
include_once("secure.php");
if($_SESSION['access'] == 'admin' and isset($_GET['email'])){
	include_once("functions.php");
	if(isset($_GET['email']) and isset($_GET['class'])){
		$email=$_GET['email'];
		$class=$_GET['class'];
		remove_associated_to_user_and_class($email,$class);
	}
}
?>