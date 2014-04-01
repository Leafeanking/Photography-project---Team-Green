<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['accessCode'])){
	$code = addslashes($_GET['accessCode']);
	$results = dbGet("select * from session where accessCode = '$code'");
	if(mysql_num_rows($results) != 0){
		$_SESSION['accessCode'] = $code;
		echo 'true';
		exit();
	}
}
echo 'false';
exit();
?>