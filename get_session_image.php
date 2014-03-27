<?php
require_once("secure.php");
require_once("functions.php");


if(isset($_GET['session']) and !isset($_GET['pull'])){
	$session = $_GET['session'];
	$result = dbGet("select * from session where accessCode = '$session'");
	if(mysql_num_rows($result) != 0){
		$data = mysql_fetch_assoc($result);
		//Send Current ImageID selected by teacher
		echo $data['imageID'];
	}
	else{
		//Session doesn't exist, send -1 for no pic.
		echo '-1';
	}
}
else if(isset($_GET['session']) and isset($_GET['pull'])){
	$session = $_GET['session'];
	$result = dbGet("select * from session where accessCode = '$session'");
	if(mysql_num_rows($result) != 0){
		$data = mysql_fetch_assoc($result);
		//Send Current Image selected by teacher
		echo "<img src='image.php?imageID=$data[imageID]'/>";
	}
	else{
		//Session doesn't exist, send text that image does not exist.
		echo 'Image Not Found';
	}
}
else{
	//No Session sent
	exit();
}


?>