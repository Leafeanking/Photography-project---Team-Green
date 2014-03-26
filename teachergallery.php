<?php
require_once("secure.php");
require_once("functions.php");
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}

//Temporary Static variables
$_SESSION['project'] = 1;
$_SESSION['accessCode'] = 'abcd';
$code = $_SESSION['accessCode'];
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
	</head>
	<body class="centerGrouping">
			<header id="logo">
			</header>
		<a href="studenthome.php" id="galleryExit" title="Exit Session" alt="Exit Session"><img src="exit.png"></a>
		<div class="galleria">
		<?php
			$data = access_full_project($_SESSION['project']);
			foreach($data as $image){
				echo $image[1];
			}
		?>
		</div>
	</body>
	<script type="text/javascript">

		Galleria.loadTheme('galleria/themes/classic/galleria.classic.js');
		
		//Manages the database, shows what image the teacher has selected.
		var AccessCode = '<?php echo $code;?>';
		var CurID = -1;
		
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
