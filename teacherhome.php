<?php
require_once("secure.php");
require_once("functions.php");
if(isset($_GET['folder'])){
	$_SESSION['view'] = $_GET['folder'];
}
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}

if(isset($_FILES['file'])){
	//Server Must have max-upload size in php.ini adjusted to allow admin to upload full class files
	print_r($_FILES);
	$zip = new ZipArchive;
	$zip->open($_FILES['file']['tmp_name']);
	$zip->extractTo(ini_get('upload_tmp_dir'));
	//$project = $_POST['project'];
	for($i = 0;$i < $zip->numFiles; $i++){
		$owner = $zip->getNameIndex($i);
		$owner = trim(str_replace(range(0,9),'',$owner));
		$owner = str_replace("'",'',$owner);
		$owner = str_replace('_',' ',$owner);
		$owner = strtolower($owner);
		$owner = str_replace(array('.jpg','.png','.gif'),'',$owner);
		$name = '/'.$zip->getNameIndex($i);
		$image = addslashes(file_get_contents(ini_get('upload_tmp_dir').$name));
		dbGet("insert into images (owner,data,projectID) values ('$owner','$image',4)");
		unlink(ini_get('upload_tmp_dir').$name);
	}
	
}

set_time_limit(100);
include_once('javafunctions.php');
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="photography.css">
		<title>DSU Photography</title>
		<script src="photography.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script src="dropit.js"></script>
		<link rel="stylesheet" href="dropit.css" type="text/css" />
	</head>
	<body>
	
<!--Create Project, hidden div------------------------------------------------------------------------->
		<div id = "create_project">
		<h3>Create Project</h3>
		<button onclick='switch_to_edit_classes()'>Edit Classes</button>
		<form method = 'post' action = 'teacherhome.php'>
		Class: 
		<?php  
			$classes = get_classes();
			echo "<select name='class'>";
			foreach($classes as $class){
				echo "<option value='$class'>$class</option>";
			}
			echo "</select>";
		?>
		<br/>Theme: <input type='text' name='theme'>
		<?php 
			//Each Question is in a separate div, so that when you select the Scale of the previous one, a new question is made 
			//available if you want to create a new one. Goes up to 20, by restriction of the database structure, and this particular code.
			for($i = 1; $i <= 20;$i++){
				$next = $i+1;
				if($i==1){echo "<div id='project_create_question_$i'>Question $i: <input type='text' name='question$i'>";}
				else{echo "<div id='project_create_question_$i' style='display:none;'>Question $i: <input type='text' name='question$i'>";}
				echo " Scale (2-10): <select name='scale$i' onclick='show_next_create_question_field($next)'>";
				echo "<option value='none' selected>Select</option>";
				for($j=2;$j<=10;$j++){
					echo "<option value=$j>$j</option>";
				}
				echo "</select>";
				echo "</div>";
			}
		?>
		<i>Select a scale to move onto another question or create project</i></br>
		<input type='submit' value='Create New Project' name='create_project'>
		</form>
		</div>
		
<!--Edit Class, hidden div------------------------------------------------------------------------------------------>		
	<div id='edit_classes'>
		<h3>Create Class</h3>
		<form target='teacherhome.php' method='post'>
			<input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
			<input type='text' name='class'>
			<input type='submit' name='create_class' value='Create New Class'>
		</form>
		<h3>Delete Class</h3>
		<?php 
			$data = get_classes();
			echo "<form action='teacherhome.php' method='post'>";
			echo "<select name='class'>";
			echo "<option value='none'>Select</option>";
			foreach($data as $class){
				echo "<option value='$class'>$class</option>";
			}
			echo "</select>";
			echo "<input type='submit' name='delete_class' value='Delete Class'>";
			echo "</form>";
		?>
	</div>
		
<!--Upload Images, hidden div------------------------------------------------------------------------------------------>
	<div id='manage_images'>
	<form target='teacherhome.php' method='post' enctype='multipart/form-data'>
		<!--Server Must have max-upload size in php.ini adjusted to allow admin to upload full class files -->
		<input type='file' name='file'>
		
		<input type='submit' name='upload_file' value='Upload'>	
	</form></div>	
<!--Actual Page------------------------------------------------------------------------------------------------------>		
		<div id = "logo" class="center">
            <header >
                <h1>Photography</h1>
            </header>
			
		</div>
        
        <div class="redline links">
			<ul class="menu">
            
               <li class="left avatar"> 
               		<a> <img src="icon3.png" alt="avatar" height="64" width="64"></a> 
               </li>
               <li class="left"><a href="name">Name</a> 
               </li>
				<li class='left'>
					<a href="#">Manage</a>
					<ul>
						<li>
							<a onclick="show_create_project()">Create Project</a>
						</li>
						<li>
							<a onclick="show_edit_classes()">Edit Classes</a>
						</li>
					</ul>
				</li>
                <li class="right">
                    <form action='index.php' method='POST'>
                    <input type='submit' name = 'Logout' value='Logout'>
                    </form>
                </li>
				<li class="right"> <button onclick="Join()">Start</button>
			</ul>
        </div>
       
		<div class="center links">
		<?php
                        if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
                            echo "<div id='location'>";
                            $theme = get_theme($_SESSION['view']);
                            echo "<a href='teacherhome.php?folder=home'>Home</a> Gallery: $theme";
 
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
			<!--Javascript for the drop down menu-->
			<script type="text/javascript">
			$(document).ready(function() {
				$('.menu').dropit();
			});
			</script>
	</body>
</html>
