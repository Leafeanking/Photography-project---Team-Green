<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
	$_SESSION['view'] = $_GET['folder'];
}
print_r($_FILES);
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<script  src="photography.js"></script>
		<script src="javafunctions.js"></script>
	</head>
	<body>
	<div id='shadow' onclick='hide_all()'></div>
	<!--Upload Images, hidden div------------------------------------------------------------------------------------------>
	<div id='manage_images'>
	<form target='nowhere.php' method='post'>
		<!--Server Must have max-upload size in php.ini adjusted to allow admin to upload full class files -->
		<input type='text' name='file'></input>
		
		<input type='submit' name='upload_file' value='Upload'></input>
	</form></div>	
	
	<!--Actual Page------------------------------------------>
		<div id = "logo" class="center">
		<header>
				<h1 >Photography</h1>
		</header>
			
		</div>
		<div class="redline links">
		<ul>
	
			<li class="left avatar"> <a> <img src="icon3.png" alt="avatar" height="64" width="64"></a> </li>
			<li class="left"> <a href="name">Name</a> </li>
			<li class="left"><button onclick=show_manage_images()>Upload Images</button>
			<li class="right "> <form action='index.php' method='POST'>
					<input type='submit' name = 'Logout' value='Logout'>
					</form>
			</li>
			<li class="right">
					<button onclick="Join()">Join</button>
			</li>
		
				
			</ul>
		</div>
		<div class="center links" >
		<?php
					if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
						echo "<div id='location'>";
						$theme = get_theme($_SESSION['view']);
						echo "<a href='studenthome.php?folder=home'>Home</a> >> Gallery: $theme";
					}
				?>
			<?php
			if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
				include 'viewfolder.php';
			}
			else{
				include 'viewhome.php';
			}
			?>
		</div>
		<footer>
			<small><i>
				Copyright &copy; 2014 <br />
					<a href= "mailto:scawley@dmail.dixie.edu">sara@cawley.com</a>
			</i></small>
		</footer>
		
	</body>
</html>
