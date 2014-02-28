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
<!------------------------------------------------------------------------------------------>
<!--Shows what folder you are currently viewing. Grab everything inside of this and place it where you want it-->
<?php
	if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
		echo "<div id='location'><ul>";
		echo "<li><a href='studenthome.php?folder=home'>Home</a></li>";
		$theme = get_theme($_SESSION['view']);
		echo "<li>$theme</li></ul></div>";
	}
?>
<!-------------end-------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
	</head>
	<body>
		<div id = "logo">
            <header >
                <h1 >Photography</h1>
            </header>
		</div>
            <nav>
                <a> 
                	<img src="icon3.png" alt="avatar" height="64" width="64">
                </a>
                <a> 
                	<button type="button">Join!</button>
                </a> 
            </nav>
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