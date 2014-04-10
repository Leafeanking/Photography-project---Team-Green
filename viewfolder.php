<?php
require_once("secure.php");
require_once("functions.php");
if(!isset($_SESSION['view']) or $_SESSION['view'] == 'home'){
	if($_SESSION['access'] == 'admin'){
		header('Location: teacherhome.php');
	}
	else{
		header('Location: studenthome.php');
	}
}

if($_SESSION['access'] == 'admin'){
	$data = access_full_project($_SESSION['view']);
}
else{
	$data = access_personal_project($_SESSION['username'],$_SESSION['view']);
}
//Image and comments Popup window.
echo "<div id='image_and_comments'>";
	echo "<div id='comments_ratings'></div>";
	echo "<div id='selectedImage'></div>";
echo "</div>";

///////////////////////////////////////////
$theme = get_theme($_SESSION['view']);

echo "<center><h1>$theme</h1></center>";

echo "<div>";
$owner = "";
foreach($data as $image){
	//$image[0] is the image id, which will be sent by javascript to the server to get the comments back. 
	//it is also so that the javascript can get the content by id and put it into the main viewer to foccus on the image.
	
	if(isset($image[2]) and $owner != $image[2]){
		$owner  = $image[2];
		$name = email_to_username($owner);
		echo "<div class='clear'><h2>$name</h2></div>";
	}
	echo "<div class='folder'><div id='$image[0]' onclick ='manage_image_click($image[0])'>$image[1]</div></div>";
}

echo "</div>";
echo "<div class='clear'></div>";
?>
