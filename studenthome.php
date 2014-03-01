<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
	$_SESSION['view'] = $_GET['folder'];
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
		<div id = "logo" class="center">
            <header >
                <h1 >Photography</h1>
            </header>
		</div>
        
        <div class="center">
            <ul>
               <li> <a> <img src="icon3.png" alt="avatar" height="64" width="64"></a> </li>
               <li> <a href="name">Name</a> </li>
               <li> <a href="back">Back</a> </li>
               <li> <a href="join">Join!</a> </li>
               
               
                <?php
					if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
						echo "<div id='location'>";
						echo "<li><a href='studenthome.php?folder=home'>Home</a></li>";
						$theme = get_theme($_SESSION['view']);
						echo "<li>$theme</li></div>";
					}
					?>
                <li>join button</li>
               
                	<li><a><button type="button">Join!</button></a> </li> 
              </ul>
        </div>
        
		<div>
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