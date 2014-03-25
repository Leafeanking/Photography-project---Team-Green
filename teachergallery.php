<?php
require_once("secure.php");
require_once("functions.php");
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}

//Temporary Static variables
$_SESSION['project'] = 1;
$_SESSION['accessCode'] = 'abcd';
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<!--Image Slider Galleria-->
		<script src="jquery.min.js"></script>
		<script type="text/javascript" src="galleria/galleria-1.3.5.min.js"></script>
		<link rel="stylesheet" href="dropit.css" type="text/css" />
		<link rel="stylesheet" href="galleria/themes/classic/galleria.classic.css" type="text/css" />
	
	</head>
	<body class="centerGrouping">
		<div id = "logo">
			<header >
				<h1></h1>
			</header>
		</div>
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
		var ImageID=0;
		function update_database_image(){
			var active = document.getElementsByClassName("galleria-image active");
			var divv = active[0];
			if(divv != undefined){
				//alert(divv.getChildNodes);
				//alert(divv.getClassName());
				//var imagelist = divv.getElementByTagName('*');
				//alert(imagelist);
			}
		}
		Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
		Galleria.run('.galleria'); // initialize the galleria
		
		// do something when someone clicks an element with the ID 'mylink'
		$('#enterFullscreen').click(function() {
				$('.galleria').data('galleria').enterFullscreen(); // will start slideshow attached to #image when the element #play is clicked
					
					});
		Galleria.run('.galleria');
		
		/*
		var elm = document.getElementsByClassName("galleria-image active");
		elm.onreadystatechange = function{
			alert("hello");
		}*/
	</script>
</html>
