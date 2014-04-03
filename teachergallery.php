<?php
require_once("secure.php");
require_once("functions.php");
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}

//Temporary Static variables
if(isset($_POST['project']) and isset($_POST['accessCode'])){
	$_SESSION['project'] = $_POST['project'];
	$_SESSION['accessCode'] = $_POST['accessCode'];
}
else if(!isset($_SESSION['project']) or !isset($_SESSION['accessCode'])){
	header("Location: index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<!--Image Slider Galleria-->
		<script src="jquery.min.js"></script>
		<script type="text/javascript" src="galleria/galleria-1.3.5.js"></script>
		<link rel="stylesheet" href="dropit.css" type="text/css" />
		<style>
		</style>
		<script>
		var AccessCode = '<?php echo $_SESSION['accessCode'];?>';
		var CurID = -1;
		
		function update_meta_data(){
			var temp = CurID
			var view = document.getElementById('image_meta_data');
			view.innerHTML = 'Please Wait for Metadata';
			var ajx = new XMLHttpRequest();
			ajx.onreadystatechange = function(){
				if(CurID != temp){
					ajx.abort();
					return;
				}
				else if(ajx.readyState == 4){
					view.innerHTML = ajx.responseText;
				}
			}
			ajx.open("GET","get_meta_data.php?imageID="+CurID,true);
			ajx.send();
		}
		
		function end_session(){
			var ajx = new XMLHttpRequest();
			ajx.open("GET", "close_session.php?session="+AccessCode,false);
			ajx.send();
		};
		window.onbeforeunload = function(){
			end_session();
		};
		</script>
	</head>
	<div class='links' style='float:right;'>
		<a href='studentgallery.php' target='_blank'>Join Voting</a>
	</div>
	<body class="centerGrouping">
			<header id="logo">
			</header>
		<a href="index.php" id="galleryExit" title="Exit Session" alt="Exit Session"><img src="exit.png"></a>
		<div class="galleria">
		<?php
			$data = access_full_project($_SESSION['project']);
			foreach($data as $image){
				echo $image[1];
			}
		?>
		</div>
		<div id='image_meta_data'></div>
		
	</body>
	<script type="text/javascript">
		alert("Might not view properly unless in FireFox");

		Galleria.loadTheme('galleria/themes/classic/galleria.classic.js');
		
		//Manages the database, shows what image the teacher has selected.
		
		
		Galleria.on('image',function(e){
			Galleria.log(this);
			Galleria.log(e.imageTarget);
			var str = e.thumbTarget.outerHTML;
			var n = str.search("imageID");
			if(n!=-1){
				var sec_half = str.split("imageID=")[1];
				var quote = sec_half.search('"');
				var num = sec_half.substr(0,quote);
				var picID = parseInt(num);
				if(CurID != picID){
					CurID = picID;
					var ajx = new XMLHttpRequest();
					ajx.open("GET", "update_session_image.php?imageID="+CurID+"&session="+AccessCode,true);
					ajx.send();
				}
				update_meta_data();
			}
			else{
				alert("ImageID not found, Updates to database are not being made.");
			}
		});
		
		Galleria.run('.galleria'); // initialize the galleria
		
		
		// do something when someone clicks an element with the ID 'mylink'
		$('#enterFullscreen').click(function() {
				$('.galleria').data('galleria').enterFullscreen(); // will start slideshow attached to #image when the element #play is clicked
					
					});
		Galleria.run('.galleria');
	</script>
</html>
