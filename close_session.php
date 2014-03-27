<?php
require_once("secure.php");
if($_SESSION['access']=='admin'){
	require_once("functions.php");
	if(isset($_GET['session'])){
		dbDo("delete from session where accessCode = '$_GET[session]'");
	}
}
?>