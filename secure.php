<?php
//include at the beginning of every page that you want to be secure.
session_start();
if(!isset($_SESSION['access']) or $_SESSION['access'] == false){
	header('Location: index.php');
}
?>