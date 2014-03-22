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
			$results = dbGet("select username,email from users where access ='$class'");
			echo "<table>";
			while($student = mysql_fetch_assoc($results)){
				echo "<tr>";
					echo "<td>$student[username]</td>";
					echo "<td>$student[email]</td>";
					$delete = '"'.$student['email'].'"';
					echo "<td><button onclick='admin_delete_student_from_class($delete,$_GET[class])'>Delete Student</button></td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
}
?>