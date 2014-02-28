<script>
	
	function request_comments(photoId){
		var comments = document.getElementById("comments_ratings");
		var ajx = new XMLHttpRequest();
		ajx.onreadystatechange = function(){
			comments.innerHTML = ajx.responseText;
		}
		ajx.open("GET", "getcomments.php?imageId="+photoId,true);
		ajx.send();
	}
	
	function manage_image_click(photoId){
		document.getElementById('image_and_comments').style.display = 'block';
		document.getElementById('shadow').style.display='block';
		request_comments(photoId);
	}
	
	function hide_image_and_comments(){
		document.getElementById('image_and_comments').style.display = 'none';
		document.getElementById('shadow').style.display = 'none';
	}
</script>

<?php
require_once("secure.php");
require_once("functions.php");
if(!isset($_SESSION['view']) or $_SESSION['view'] == 'home'){
	header('Location: studenthome.php');
}
$data = access_personal_project($_SESSION['username'],$_SESSION['view']);
echo "<div id='image_and_comments'>";
echo "<div id='selectedImage'></div>";
echo "<div id='comments_ratings'></div></div>";
echo "<div id='shadow' onclick='hide_image_and_comments()'></div>";
echo "<div id='iconbar'>";

foreach($data as $image){
	//$image[0] is the image id, which will be sent by javascript to the server to get the comments back. 
	//it is also so that the javascript can get the content by id and put it into the main viewer to foccus on the image. 
	echo "<div id='$image[0]' onclick ='manage_image_click($image[0])' >$image[1]</div>";
}

echo "</div>";
echo "<div class='clear'></div>";
?>
