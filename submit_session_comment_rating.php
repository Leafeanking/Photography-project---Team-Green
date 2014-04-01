<?php
require_once('secure.php');
if(isset($_POST) and isset($_POST['user']) and isset($_POST['imageID']) and isset($_POST['comments'])){
	require_once('functions.php');
	$user = $_POST['user'];
	$imageID = $_POST['imageID'];
	$comments = addslashes($_POST['comments']);
	$comments = str_replace("'","`",$comments);
	unset($_POST['user']);
	unset($_POST['imageID']);
	unset($_POST['comments']);
	dbDo_noErr("insert into comments (imageID,author,comment) values ('$imageID','$user','$comments')");
	foreach($_POST as $key => $var){
		$value = 'v'.$var;
		$query = "update ratings set $value = $value + 1 where questionID = '$key' and imageID = '$imageID'";
		//echo $query; //use for debugging.
		dbDo_noErr($query);
	}
}
?>