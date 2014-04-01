<?php
include_once("secure.php");
include_once("functions.php");
	if(isset($_GET['class'])){
		$data = list_viewable_no_pic($_SESSION['username'],addslashes($_GET['class']));
		echo "Project: <select name='project'>";
		echo "<option value='none' selected>Choose</option>";
		foreach($data as $project){
			echo "<option value = $project[0]>$project[1]</option>";
		}
		echo "</option>";
		//Teacher uses this function for selecting project to use in a session. 
		if($_SESSION['access']=='admin'){
			echo "<input type='submit' name='start_session' value='Start Session'>";
		}
		//Student uses this function for uploading images to the database. 
		else{
			echo "<input type='submit' name='upload_file' value='Upload'>";
		}
	}
?>