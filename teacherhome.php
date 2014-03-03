<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
	$_SESSION['view'] = $_GET['folder'];
}
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}
?>
<!-- Temporary form to use for testing. We need to incorperate this into the picture icon.-->
<form action='index.php' method='POST'>
	<input type='submit' name = 'Logout' value='Logout'>
</form>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
	</head>
	<body> 
		<div id = "create_project"></div>
		<div id = "logo" class="center">
            <header >
                <h1 >Photography</h1>
            </header>
			<?php
					if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
						echo "<div id='location'>";
						echo "<li><a href='teacherhome.php?folder=home'>Home</a></li>";
						$theme = get_theme($_SESSION['view']);
						echo "<li><b>Gallery:</b> $theme</li></div>";
					}
			?>
		</div>
        
        <div class="center">
        
            <ul class="redline navwrapper">
            
               <li class="left avatar"> <a> <img src="icon3.png" alt="avatar" height="64" width="64"></a> </li>
               <li class="left"> <a href="name">Name</a> </li>
                <li class="right"> <a href="join">Join!</a> </li>
               <li class="right"> <a href="back">Back</a> </li>
                <li><a><button type="button">Join!</button></a> </li>
         	</ul>
        </div>
       
		<div class="center">
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