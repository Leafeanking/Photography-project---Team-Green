<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
		$_SESSION['view'] =	addslashes($_GET['folder']);
	}

?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
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
		<a href="teacherhome.php" id="manageImagesExit" title="Exit" alt="Exit"><img src="exit.png"></a>
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

	<!--Update avatar hidden div----------------------------->
	<div id='update_avatar'>
		<h2>Update avatar</h2>
		<p>Images are cropped by height</p>
		<form action='index.php' method='post' enctype="multipart/form-data">
			<input type='file' name='file' required></input>
			<input type='submit' name='update_avatar' value='Update Avatar'>
		</form>
	</div>
	
	<!--Actual Page------------------------------------------>

		<header id="logo">
		</header>
			
		</div>
		<div class="redline links">
		<ul>
	
			<li class="left avatar"> <a onclick='show_update_avatar()'><div id='avatar'><img src="avatar.php?email=<?php echo $_SESSION['username']?>" alt="avatar" title="Change Picture"></div></a> </li>
			<?php
				if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
					echo "<li class='left'>";
					echo "<a href='index.php?folder=home'>Back</a>";
					echo "</li>";
				}
				else{
					$name = username_from_email($_SESSION['username']);
					echo "<li class='left'> <a href='profileupdate.php'>$name</a> </li>";
					echo "<li class='left'><button onclick=show_manage_images()>Upload Images</button>";
				}
			?>
			
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
		<footer class="copyright">
			<h4><small ><i>
				Copyright &copy; 2014 <br />
			</i></small></h4>
            <br>
		</footer>		
	</body>
</html>

