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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		var imageID = -1;
		var code = '<?php echo $_SESSION['accessCode'];?>';
		var storedInfo = {};
		var user = '<?php echo $_SESSION['username'];?>';
		
		function save_data(){
			storedInfo = {};
			var votes = document.getElementById('vote_sliders').getElementsByTagName('input');
			for(var i = 0; i < votes.length; ++i){
				storedInfo[votes[i].name] = votes[i].value;
			}
			storedInfo['comments'] = document.getElementById('commentBox').value;
			storedInfo['imageID'] = imageID;
			storedInfo['user'] = user;
			document.getElementById('notify_save').innerHTML = "<h3 style='color:white;'>Your data has been saved, you can continue to make changes and press 'Save'. Your data will submit as soon as the image changes or window closes.</h3>";
			//In case if screen is not refreshing, save data button
			//can be resorted to as a panic button to make program work. 
			check_image();
		}
		
		function submit_data(){
			if(!jQuery.isEmptyObject(storedInfo)){
				jQuery.ajax({
					type: 'POST',
					url: "submit_session_comment_rating.php",
					data: storedInfo,
					success: function(data){
						document.getElementById('notify_save').innerHTML = "";
					}, //Use for troubleshooting.
					async: false
					});
			}
		}
		
		function update_selected_value(id){
			var sliderval = document.getElementById('slider'+id).value;
			document.getElementById(id).innerHTML = sliderval;
		}
		
		//Update Voting form. 
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
		
		function update_meta_data(){
			var temp = imageID;
			var view = document.getElementById('image_meta_data');
			view.innerHTML = 'Please Wait for Metadata';
			var ajx = new XMLHttpRequest();
			ajx.onreadystatechange = function(){
				if(imageID != temp){
					ajx.abort();
					return;
				}
				else if(ajx.readyState == 4){
					view.innerHTML = ajx.responseText;
				}
			}
			ajx.open("GET","get_meta_data.php?imageID="+imageID,true);
			ajx.send();
		}
		
		//Update Image
		function update_image(){
			//Get new image and fill in space.
			var ajximg = new XMLHttpRequest();
			ajximg.onreadystatechange = function(){
				//update image. 
				if(ajximg.readyState==4){
					var view = document.getElementById('session_image_view');
					view.innerHTML = ajximg.responseText;
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
							//All processed at time that screen changes. 
							//Send saved data
							submit_data();
							//Delete saved data
							storedInfo = {};
							//Empty Comment box
							document.getElementById('commentBox').value = '';
							//Get new image
							update_image();
							//Refresh Vote Form
							get_vote_form();
							//Get meta data
							update_meta_data();
							
						}
				};
			ajx.open("GET","get_session_image.php?session="+code,true);
			ajx.send();
		}
		
		//Make sure that any daved form data is submitted before closing window.
		window.onbeforeunload = function(){
			submit_data();
			storedInfo = {};
		};
	</script>	
	</head>
	<body class="centerGrouping">
			<header>
			</header>
		<!--Large picture-->
		<a href="index.php" id="galleryExit" title="Exit Session" alt="Exit Session"><img src="exit.png"></a>
		<div class="galleriaStudent blackBorder">
			<!--place holder picture-->
			<div id='session_image_view'>
			</div>
			<div id='image_meta_data'>
			</div>
			<div id='vote_sliders'>
			</div>
			<textarea id="commentBox" name="comments" placeholder="Comments"></textarea>
			<div id='notify_save'></div>
			<input id="submitComment" type="submit" name="submit" value="Save" onclick='save_data()'></input>
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


