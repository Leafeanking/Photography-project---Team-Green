<?php
include_once("secure.php");
if($_SESSION['access'] == 'admin' and isset($_GET['email'])){
	include_once("functions.php");
	$email=$_GET['email'];
	remove_associated_to_user($email);
}
?>