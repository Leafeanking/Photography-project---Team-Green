<?php
require_once("secure.php");
require_once("functions.php");
if($_SESSION['access'] != 'admin'){
	header('Location: index.php');
}
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
		<script src="photography.js"></script>
		<script src="jquery.min.js"></script>
		<script src="dropit.js"></script>
		<script src="javafunctions.js"></script>
		<link rel="stylesheet" href="dropit.css" type="text/css" />
		<script  type="text/javascript">
		function admin_delete_student_from_class(email,clas){
			clas = clas.replace(' ', "%20");
			var result = confirm("Want to delete?");
			if (result==true) {
				var ajx = new XMLHttpRequest();
				ajx.onreadystatechange = function(){
					show_students_table();
				}
				ajx.open("GET", "delete_student_from_class.php?email="+email+"&class="+clas,true);
				ajx.send();
			}
		}
		
		function Create_session() {
			var access=prompt("Enter access code to join the session");
			//if they typed in the right access code, they jump to the session page
			if(access=="true"){
				window.location="sessionpage.php";
			}
			else{
				alert("There is no session going on with that access code.");
			}
		}
		</script>
	</head>
	<body>
	<div id='shadow' onclick='hide_all()'></div>
<!--Create Project, hidden div------------------------------------------------------------------------->
		
		
		<div id = "edit_project">
			<div id = "create_project">
				<h2>Create Project</h2>
				<button onclick='switch_to_edit_classes()'>Edit Classes</button>
				<form method = 'post' action = 'index.php'>
				Class: 
				<div id='projects_choose_single'>
				<?php  
					$classes = get_classes();
					echo "<select name='class'>";
					echo "<option value = 'none'>Choose</option>";
					foreach($classes as $class){
						echo "<option value='$class'>$class</option>";
					}
					echo "</select>";
				?>
				<input type='checkbox' onclick="projects_select_multiple_classes()">Choose Multiple.
				</div>
				<div id="project_choose_multiple">
				<?php  
					foreach($classes as $class){
						echo "<input type='checkbox' name='classes[]' value='$class'>$class<br/>";
					}
				?>
				</div>
				<br/>Title: <input type='text' name='theme'><br/>
				<?php 
					//Each Question is in a separate div, so that when you select the Scale of the previous one, a new question is made 
					//available if you want to create a new one. Goes up to 20, by restriction of the database structure, and this particular code.
					for($i = 1; $i <= 20;$i++){
						if($i==1){
							echo "<div id='project_create_question_$i'>Question $i: <input type='text' name='question$i' placeholder='Leave empty for nothing.'>";
						}
						else{
							echo "<div id='project_create_question_$i' style='display:none;'>Question $i: <input type='text' name='question$i' placeholder='Leave empty for nothing.'>";
						}
						echo " Scale 1 to: <select name='scale$i'>";
						echo "<option value='none' selected>Select</option>";
						for($j=2;$j<=10;$j++){
							echo "<option value=$j>$j</option>";
						}
						echo "</select>";
						echo "</div>";
						
					}
					echo "<button type='button' onclick='show_next_create_question_field();return false;'>+</button>";
				?>
				<br/>
				<br/>
				<input type='submit' value='Create New Project' name='create_project'>
				</form>
			</div>
			<div id='delete_project'>
				<h2>View and Delete projects</h2>
				<?php
					$results = dbGet("select * from projects order by class");
					$class = '';
					$quote = '"';
					$confirm = "return confirm('Deleting a project will remove all connected images, comments, and ratings associated with this project. Do you want to do this?')";

					while($projects = mysql_fetch_assoc($results)){
						if($class != $projects['class']){
							$class = $projects['class'];
							echo "<hr><h3>$class</h3>";
						}
						echo "<table><tr><td><u>$projects[theme]</u></td></tr></table>";
						echo "<table><tr><th>Scale</th><th>Question</th></tr>";
						for($i=1;$i<=20;$i++){
							$question = $projects['q'.$i];
							$scale = $projects['scale'.$i];
							if($question!= NULL and $scale != NULL){
								
								echo "<tr><td>$scale</td><td>$question</td></tr>";
							}
							else{
								break;
							}
						}
						echo "</table>";
						echo "<form action = 'index.php' method = 'POST' onsubmit=$quote$confirm$quote>";
						echo "<input type='hidden' name='projectID' value='$projects[projectID]'>";
						echo "<input type='submit' name = 'delete_project' value = 'Delete Project' >";
						echo "</form><br/><br/>";
					}
				?>
			</div>
		</div>
		
<!--Edit Class, hidden div------------------------------------------------------------------------------------------>		
	<div id='edit_classes'>
		<div id='class_create_delete'>
			<h3>Create Class</h3>
			<form action='index.php' method='post'>
				<input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
				<input type='text' name='class'>
				<input type='submit' name='create_class' value='Create New Class'>
			</form>
			<h3>Delete Class</h3>
			
			<!--
			!!!Fix delete Class to work along with multi-class function. 
			!!!
			-->
			
			
			<?php 
				$data = get_classes();
				$quote = '"';
				$confirm = "return confirm('Deleting a class will remove all connected students, images, comments, ratings and projects associated with this class. Do you want to do this?')";
				echo "<form action='index.php' method='post' onsubmit=$quote$confirm$quote>";
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
		<div id='students_in_class'>
			<select name="view_class_students" id='students_in_class_selector'
				onchange="show_students_table()">
			<?php
				$data = get_classes();
				echo "<option value='none'>Select</option>";
				foreach($data as $class){
					echo "<option value='$class' onclick=''>$class</option>";
				}
			?>
			</select>
			<div id='students_in_class_table'>
			
			</div>
		</div>
	</div>
		

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
               <li class="left"><a href="profileupdate.html">Name</a> 
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
				<li class="right"> <button onclick="Create_session()">Start</button>
			</ul>
        </div>
       
		<div class="center links">

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
