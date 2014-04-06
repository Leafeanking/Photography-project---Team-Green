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
			
			var ajx = new XMLHttpRequest();
			ajx.open("GET", "delete_student_from_class.php?email="+email+"&class="+clas,false);
			ajx.send();
		}
		
		function admin_delete_selected_students(){
			var clas = document.getElementById('class_for_student_deletion').value;
			var result = confirm("Ready to delete students from class?");
			if (result==true) {
				var selected = document.getElementsByClassName('delete_students_checkbox');
				for(var i = 0; i < selected.length; i++){
					if(selected[i].checked){
						admin_delete_student_from_class(selected[i].value,clas);
					}
				}
				show_students_table();
			}
		}
		
		function show_session() {
			show_element('create_session');
			show_element('shadow');
		}
		</script>
	</head>
	<body>
	<div id='shadow' onclick='hide_all()'></div>
<!--Create Session, hidden div------------------------------------------------------------------------->
	<div id = 'create_session'>
		<form action='teachergallery.php' method='POST'>
		Access Code:<input type = 'text' name='accessCode' id='session' required><br/>
		<?php
			echo "Class: <select id='multi-class-selector' name='class' onchange='show_projects_by_class_dropdown()'>";
			echo "<option value='none'>Select</option>";
			$results = dbGet("select class from classes");
			while($class = mysql_fetch_assoc($results)){
				$class = $class['class'];
				echo "<option value='$class'>$class</option>";
			}
			echo "</select>";
		?>
		<div id='class_projects_session'>
		</div>
		</form>
	</div>
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
        
		<div id='students_in_class'><br>
        	<h3>Delete Students</h3>
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
	
	
	<!--Update avatar hidden div----------------------------->
	<div id='update_avatar'>
		<h2>Update avatar</h2>
		<form action='index.php' method='post' enctype="multipart/form-data">
			<input type='file' name='file'></input>
			<input type='submit' name='update_avatar' value='Update Avatar'>
		</form>
	</div>
		

<!--Actual Page------------------------------------------------------------------------------------------------------>	

	<?php
	if(isset($_SESSION['view']) and $_SESSION['view'] != 'home'){
        echo "<div id='location' class='breadcrumb links'>";
        $theme = get_theme($_SESSION['view']);
        echo "<a href='index.php?folder=home'>Home</a> >> Gallery: $theme";
		echo "</div>";
	}
	?>	
            <header id="logo">
            </header>
			
		</div>
        
        <div class="redline links">
			<ul class="menu">
            
               <li class="left avatar"> 
               		<a onclick='show_update_avatar()'><div id='avatar'><img src="avatar.php?email=<?php echo $_SESSION['username']?>" alt="avatar" title="Change Picture"></div></a> 
               </li>
			   <?php
			   $name = username_from_email($_SESSION['username']);
               echo "<li class='left'><a href='profileupdate.php'>$name</a></li>"; 
			   ?>
               
				<li class='left'>
					<a href="#">Manage</a>
					<ul>
						<li>
							<a onclick="show_create_project()">Projects</a>
						</li>
						<li>
							<a onclick="show_edit_classes()">Classes</a>
						</li>
					</ul>
				</li>
                <li class="right">
                    <form action='index.php' method='POST'>
                    <input type='submit' name = 'Logout' value='Logout'>
                    </form>
                </li>
				<li class="right"> <button onclick="show_session()">Start</button>
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
		<footer class="copyright">
			<h4><small ><i>
				Copyright &copy; 2014 <br />
			</i></small></h4>
            <br>
		</footer>
			<!--Javascript for the drop down menu-->
			<script type="text/javascript">
			$(document).ready(function() {
				$('.menu').dropit();
			});
			</script>
	</body>
</html>
