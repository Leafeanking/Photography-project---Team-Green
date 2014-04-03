<?php
require_once("secure.php");
require_once("functions.php");

	define("MAXSECONDS",600);//10 MINUTES
	
	//Check for how the image is being retrieved. 
	if(isset($_GET['email'])){
		$data = addslashes($_GET['email']);
		$flag = 'email';
	}
	else if(isset($_GET['idnum'])){
		$data = addslashes($_GET['idnum']);
		$flag = 'idnum';
	}
	else{
		exit();
	}
	
	if($flag == 'email'){
		$query = "select avatar from users where email='$data'";
	}
	else{
		$query = "select avatar from users where idnum = $data";
	}

	$results = dbGet($query);
	if(mysql_num_rows($results)!=0){
		$data = mysql_fetch_row($results);
		if($data[0] != ''){
			$data = $data[0];
		}
		else{
			$data = file_get_contents('no_avatar.jpg');
		}	
	}
	else{
		$data = file_get_contents('no_avatar.jpg');
	}
	header("content-type: image/jpeg");
	echo $data;
		

?>