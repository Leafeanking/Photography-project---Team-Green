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
		<script>
		function admin_delete_student(email){
			var result = confirm("Want to delete?");
			if (result==true) {
				var ajx = new XMLHttpRequest();
				ajx.onreadystatechange = function(){
					show_students_table();
				}
				ajx.open("GET", "delete_student.php?email="+email,true);
				ajx.send();
			}
		}
		
		</script>
	</head>
	<body>
	<div id='shadow' onclick='hide_all()'></div>
<!--Create Project, hidden div------------------------------------------------------------------------->
		<div id = "create_project">
		<h3>Create Project</h3>
		<button onclick='switch_to_edit_classes()'>Edit Classes</button>
		<form method = 'post' action = 'index.php'>
		Class: 
		<?php  
			$classes = get_classes();
			echo "<select name='class'>";
			echo "<option value = 'none'>Choose</option>";
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
		<div id='class_create_delete'>
			<h3>Create Class</h3>
			<form action='index.php' method='post'>
				<input type="hidden" name="MAX_FILE_SIZE" value="4194304" /> 
				<input type='text' name='class'>
				<input type='submit' name='create_class' value='Create New Class'>
			</form>
			<h3>Delete Class</h3>
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
				<li class="right"> <button onclick="Join()">Start</button>
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
