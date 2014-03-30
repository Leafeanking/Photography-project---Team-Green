<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
		$_SESSION['view'] = $_GET['folder'];
	}

?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<script src="jquery.min.js"></script>
		<script  src="photography.js"></script>
		<script src="javafunctions.js"></script>
	</head>
	<body>
	<div id='shadow' onclick='hide_all()'></div>
	<!--Upload Images, hidden div------------------------------------------------------------------------------------------>
    
	<div id='manage_images'>
	<h2>Upload JPG file or Zip packed with JPG files.</h2>
	<form action='index.php' method='post' enctype="multipart/form-data">
		<!--Server Must have max-upload size in php.ini adjusted to allow admin to upload full class files -->
		<input type='file' name='file'></input>
		Class: <select name='multi-class-selector' id='multi-class-selector' onchange='show_projects_by_class_dropdown()'>
		<option value='none'>Choose</option>
		<?php
			for($i=1;$i<=MAXIMUM_CLASSES;$i++){
				if($i==1){
					$access = 'access';
				}
				else{
					$access = "access$i";
				}
				
				if(isset($_SESSION[$access])){
					echo "<option value='$_SESSION[$access]'>$_SESSION[$access]</option>";
				}
				else{
					break;
				}
			}
		?>
		</select>
		<!--This is where the javascript will fill what projects can be uploaded to.-->
		<div id= 'projects_uploadable_to'>
		</div>
	</form></div>	
	
	<!--Actual Page------------------------------------------>
    <?php
	if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
        echo "<div id='location' class='center links'>";
        $theme = get_theme($_SESSION['view']);
        echo "<a href='index.php?folder=home'>Home</a> >> Gallery: $theme";
		echo "</div>";
	}
	?>
		<header id="logo">
		</header>
			
		</div>
		<div class="redline links">
		<ul>
	
			<li class="left avatar"> <a> <img src="icon3.png" alt="avatar" title="Change Picture" height="64" width="64"></a> </li>
			<?php
				$name = username_from_email($_SESSION['username']);
				echo "<li class='left'> <a href='profileupdate.html'>$name</a> </li>";
			?>
			
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
					<a href= "greenteam@mail">greenteam@mail</a>
			</i></small>
		</footer>
		
	</body>
</html>

