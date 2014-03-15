<?php
include_once("functions.php");
	$data = $_GET['imageID'];
	$query = "select data from images where imageID = '$data'";
	$results = dbGet($query);
	$data = mysql_fetch_row($results);
	header("content-type: image/jpeg");
	header('Cache-Control: private');
	header("filename='$_GET[imageID]'");
	echo $data[0];


?>