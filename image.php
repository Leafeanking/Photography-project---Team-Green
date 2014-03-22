<?php
include_once("secure.php");
include_once("functions.php");
	//Add functionality that detects this page is requested by
	//xmlhttprequest. Also that the user has access to this picture
	//or that the picture is on the list of pictures being viewed 
	//in any session running. 

	$data = $_GET['imageID'];
	$query = "select data from images where imageID = '$data'";
	$results = dbGet($query);
	$data = mysql_fetch_row($results);
	$ETAG = "896433576890$_GET[imageID]";
	//Header stuff
	
	
	if(isset($_SERVER['HTTP_IF_NONE_MATCH']) AND $_SERVER['HTTP_IF_NONE_MATCH'] == $ETAG){
		header_remove("Expires");
		header_remove("Cache-Control");
		header('HTTP/1.1 304 Not Modified'); 
        exit(0); 
	}
	else{
		session_cache_limiter('public');
		header("content-type: image/jpeg");
		header("ETag: $ETAG"); 
		define("MAXSECONDS",300);//5 MINUTES 300 seconds
		header_remove("Cache-Control");
		header_remove("Pragma");
		header_remove("X-Powered-By");
		header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()+(MAXSECONDS)));  
		echo $data[0];
	}
	


?>