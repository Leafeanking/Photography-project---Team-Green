<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
	$_SESSION['view'] = $_GET['folder'];
}
?>
<!-- Temporary form to use for testing. We need to incorperate this into the picture icon.-->


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<script  src="photography.js"></script>
	</head>
	<body>
		<div id = "logo" class="center">
		<header>
				<h1 >Photography</h1>
		</header>
			
		</div>
		
		<div class="redline">
		<ul>
	
			<li class="left avatar"> <a> <img src="icon3.png" alt="avatar" height="64" width="64"></a> </li>
			<li class="left"> <a href="name">Name</a> </li>
		
			<li class="right"> <form action='index.php' method='POST'>
					<input type='submit' name = 'Logout' value='Logout'>
					</form>
			</li>
			<li class="right">
					<button onclick="Join()">Join</button>
			</li>
		
				
			</ul>
		</div>
		<div class="center">
		<?php
					if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
						echo "<div id='location'>";
						$theme = get_theme($_SESSION['view']);
						echo "<a href='studenthome.php?folder=home'>Home</a> Gallery: $theme";
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
