<?php
require_once("secure.php");
if(isset($_POST['accessCode'])){
	$_SESSION['accessCode'] = $_POST['accessCode'];
}
if(!isset($_SESSION['accessCode'])){
	header("Location: index.php");
}
?>
<!--<input type=range is a slider bar-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<!--Image Slider Galleria-->
		<link rel="stylesheet" href="dropit.css" type="text/css" />
	<script>
		var imageID = -1;
		var code = '<?php echo $_SESSION['accessCode'];?>';
		function update_selected_value(id){
			var sliderval = document.getElementById('slider'+id).value;
			document.getElementById(id).innerHTML = sliderval;
		}
		
		function get_vote_form(){
			var ajxvote = new XMLHttpRequest();
			ajxvote.onreadystatechange = function(){
				if(ajxvote.readyState == 4){
					var view = document.getElementById('vote_sliders');
					view.innerHTML = ajxvote.responseText;
				}
			};
			ajxvote.open("GET","get_session_image.php?session="+code+"&votes",true);
			ajxvote.send();
		}
		
		function update_image(){
			//Get new image and fill in space.
			var ajximg = new XMLHttpRequest();
			ajximg.onreadystatechange = function(){
				//update image. 
				if(ajximg.readyState==4){
					var view = document.getElementById('session_image_view');
					view.innerHTML = ajximg.responseText;
					get_vote_form();
				}
			};
			ajximg.open("GET","get_session_image.php?session="+code+"&pull",true);
			ajximg.send();
		}
		
		function check_image(){
			var ajx = new XMLHttpRequest();
			ajx.onreadystatechange = function(){
						//check if there is a new imageID\
						if(ajx.readyState ==4 && Number(ajx.responseText) != imageID){
							imageID = Number(ajx.responseText);
							if(imageID == -1){
								window.location="index.php";
							}
							update_image();
						}
				};
			ajx.open("GET","get_session_image.php?session="+code,true);
			ajx.send();
		}
	</script>	
	</head>
	<body class="centerGrouping">
			<header id="logo">
			</header>
		<!--Large picture-->
		<a href="index.php" id="galleryExit" title="Exit Session" alt="Exit Session"><img src="exit.png"></a>
		<div class="galleriaStudent blackBorder">
			<!--place holder picture-->
			<div id='session_image_view'>
			</div>
			<div id='vote_sliders'>
				
			</div>
			<textarea id="commentBox" name="comments" placeholder="Comments"></textarea>
			<input id="submitComment" type="submit" name="submit"></input>
		</div>
	</body>
</html>
<script>

function loop(){
	check_image();
	setTimeout(function(){loop();},3000);
	}
	loop()
</script>


