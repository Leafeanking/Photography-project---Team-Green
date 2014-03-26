<?php
require_once("secure.php");
require_once("functions.php");
if($_SESSION['access'] != 'admin'){
	exit();
}
else{
	if(isset($_GET['imageID']) and isset($_GET['session'])){
		$id = $_GET['imageID'];
		$session = $_GET['session'];
		$result = dbGet("select * from session where accessCode = '$session'");
		if(mysql_num_rows($result) != 0){
			dbDo("update session set imageID = '$id' where accessCode = '$session'");
		}
		else{
			dbDo("insert into session (accessCode,imageID) values ('$session',$id)");
		}
	}
	else{
		exit();
	}
}	


?>