<?php
include_once("secure.php");
if($_SESSION['access'] == 'admin'){
	include_once("functions.php");
	if(isset($_GET['class'])){
		$class = $_GET['class'];
		if($class == 'none'){
			echo "Please Select a Class";
		}
		else{
		echo "<table>";
			for($i=1;$i<=MAXIMUM_CLASSES;$i++){
				if($i==1){
				$access = "access";
				}
				else{
					$access = "access$i";
				}
				$query = "select username,email from users where $access ='$class'";
				$results = dbGet($query);
				
				while($student = mysql_fetch_assoc($results)){
					echo "<tr>";
						echo "<td>$student[username]</td>";
						echo "<td>$student[email]</td>";
						$delete = '"'.$student['email'].'"';
						$class_insert = '"'.$_GET['class'].'"';
						echo "<td><input class='delete_students_checkbox' type='checkbox' value='$student[email]'></td>";
					echo "</tr>";
				}
				
			}
			echo "</table>";
			echo "<input type='hidden' id='class_for_student_deletion' value='$_GET[class]'>";
			echo "<input type='button' onclick='admin_delete_selected_students()' value='Delete Students'>";
		}
	}
}
?>