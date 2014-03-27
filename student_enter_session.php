<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['accessCode'])){
	$results = dbGet("select * from session where accessCode = '$_GET[accessCode]'");
	if(mysql_num_rows($results) != 0){
		$_SESSION['accessCode'] = $_GET['accessCode'];
		echo 'true';
		exit();
	}
}
echo 'false';
exit();
?>