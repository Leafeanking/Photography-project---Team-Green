<?php
include_once("secure.php");
include_once("functions.php");
	
	define("MAXSECONDS",600);//10 MINUTES 600 seconds
	$data = addslashes($_GET['imageID']);
	$ETAG = "896433576890$data";
	
	//Add functionality that detects this page is requested by
	//xmlhttprequest. Also that the user has access to this picture
	//or that the picture is on the list of pictures being viewed 
	//in any session running.
	function checkpermission($id){
		if($_SESSION['access'] == 'admin'){
			return true;
		}
		$results = dbGet("select * from session where imageID = $id");
		if(mysql_num_rows($results) != 0){
			return true;
		}
		$results = dbGet("select * from images where imageID = $id and owner='$_SESSION[username]'");
		if(mysql_num_rows($results) != 0){
			return true;
		}
		return false;
	}
	
	$image_access = checkpermission($data);
	
	
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
      $if_modified_since = preg_replace('/;.*$/', '',   $_SERVER['HTTP_IF_MODIFIED_SINCE']);
    } else {
      $if_modified_since = '';
    }
	
	if($if_modified_since >= gmdate('D, d M Y H:i:s \G\M\T', time()-MAXSECONDS)){
		$expired = false;
	}	
	else{
		$expired = true;
	}
	
	//Header stuff
	
	
	if(isset($_SERVER['HTTP_IF_NONE_MATCH']) AND $_SERVER['HTTP_IF_NONE_MATCH'] == $ETAG AND !$expired AND $image_access){
		header_remove("Expires");
		header_remove("Cache-Control");
		header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', time()));
		header('HTTP/1.1 304 Not Modified'); 
        exit(0); 
	}
	else if($image_access){
		$query = "select data from images where imageID = '$data'";
		$results = dbGet($query);
		$data = mysql_fetch_row($results);
		session_cache_limiter('public');
		header("content-type: image/jpeg");
		header("ETag: $ETAG"); 
		header("Cache-Control: max-age=".MAXSECONDS.", must-revalidate"); 
		header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', time())); 
		header_remove("Pragma");
		header_remove("X-Powered-By");
		header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()+(MAXSECONDS)));  
		
		echo $data[0];
	}
	else{
		header("content-type: image/jpeg");
		header("Pragma: no-cache");
		header("Cache-Control:no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
	}
	

?>