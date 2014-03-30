<?php
include_once('functions.php');
session_start();

//LOG OUT USER
if(isset($_POST['Logout']) and session_id() != false){
	session_destroy();
}

//CHECK LOGIN CREDENTIALS 
if(isset($_POST['username']) and isset($_POST['password'])){
	$_SESSION['username'] = $_POST['username'];
	//Sets $_SESSION['access'] inside of function. Allong with access2...access10;
	authenticate($_POST['username'],$_POST['password']);
}

//CREATE USER AND LOGIN
if(isset($_POST['submit_new_user'])){

	$first = addslashes($_POST['FirstName']);
	$last = addslashes($_POST['LastName']);
	$email = addslashes($_POST['username']);
	$password1 = addslashes($_POST['password']);
	$password2 = addslashes($_POST['passwordconfirm']);
	$class = $_POST['class'];
	$firstClass = $class[0];
	//print_r($class);
	//exit();
	$results = dbGet("select * from users where email = '$email'");
	//Check if a class was selected.
	if($class == 'none'){
		header("Location: createuser.php?fail=0");
	}
	//Check if password matches in both fields.
	else if($password1 != $password2){
		header("Location: createuser.php?fail=1");
	}
	//Check if any fields were left empty.
	else if($first == '' or $last=='' or $email == '' or $password1 == ''){
		header("Location: createuser.php?fail=2");
	}
	//Check if email already exists.
	else if(mysql_num_rows($results) >=1){
		header("Location: createuser.php?fail=3");
	}
	//Check for sneaky POST requests attempting to create self as admin.
	else if($class == 'admin'){
		header("Location: createuser.php?fail=4");
	}
	//Create User.
	else{
		//Name, replace ' with ` to avoid code conflicts.
		//set all strings to lower case, and then uppercase the first letter.
		//concatenate string together. 
		$name = str_replace("'","`",
			ucfirst(strtolower(trim($first)))
			.' '.
			ucfirst(strtolower(trim($last)))
			);
		//Insert first class selected
		$query = "insert into users (username,password,access,email) values ('$name','$password1','$firstClass','$email')";
		dbDo($query);
		//Insert all other classes selected up to the maximum number of classes. 
		for($i=1;$i<MAXIMUM_CLASSES and $i<count($class);$i++){
			$nextClass = $class[$i];
			$j=$i+1;
			$access = 'access'.$j;
			$query = "update users set $access = '$nextClass' where email = '$email'";
			dbDo($query);
		}
		//Set login info
		$_SESSION['access'] = $class;
		$_SESSION['username'] = $email;
	}
}

//FORWARD WHEN LOGGING IN, OR ALREADY LOGGING IN AND SUBMITTING POST FORM DATA
if(isset($_SESSION['access']) and $_SESSION['access'] != false){
	//Manage user view for admin or student
	if(isset($_GET['folder'])){
		$_SESSION['view'] = $_GET['folder'];
	}
	////////////////////////////////////////////////////////////
	//ADMIN PROCESSES///////////////////////////////////////////
	////////////////////////////////////////////////////////////
	if($_SESSION['access'] == 'admin'){
		//PROSESS REQUESTS SENT TO INDEX.PHP
		//Delete a comment
		if(isset($_POST['delete_comment'])){
		$query = "delete from comments where imageID='$_POST[picture]' and author = '$_POST[author]'";
		dbDo($query);
		}
		//Delete a Image
		if(isset($_POST['delete_image'])){
			delete_image($_POST['imageID']);
		}
		
		//Delete a Project
		if(isset($_POST['delete_project'])){
			delete_project($_POST['projectID']);
		}
		
		//Create a project.
		if(isset($_POST['create_project'])){
			srand(time());
			function create_id($project,$question){
				$id = (string)(rand()*rand())%999999999;
				if(strlen($project) > 3){
					$id = substr(strrev($project),0,3).$id;
				}
				if(strlen($question) > 3 ){
					$id = $id.str_replace(' ','',$question);
				}
				return $id;
			}
			function create_class_project($post){
				$class = $post['class'];
				$theme = str_replace("'","`",$post['theme']);
				//Writes first question. There is always one rating question Required.
				dbDo("insert into projects (class,theme) values ('$class','$theme')");
				$projectID = mysql_insert_id();
				for($i = 1;$i <= MAXIMUM_QUESTIONS;$i++){
					if($post['question'.$i] != '' and $post['scale'.$i] != ''){
						$question = str_replace("'","`",$post['question'.$i]);
						$scale = $post['scale'.$i];
						$qid = create_id($theme,$question);
						$qidIdentifier = 'q'.$i.'ID';
						$query = "update projects set q$i = '$question', scale$i = '$scale', $qidIdentifier = '$qid' where projectID = $projectID";
						dbDo($query);
					}
					else{
						break;
					}
				}
			
			}
			
			if(count($_POST['classes']) == 0){//Single Class
				create_class_project($_POST);
			}
			else{//Multiple classes
				foreach($_POST['classes'] as $class){
					$_POST['class'] = $class;
					create_class_project($_POST);
				}
			}
		}
		
		//Create class
		if(isset($_POST['create_class']) and $_POST['class'] != ''){
			$class = str_replace("'","`",$_POST['class']);
			$query = "insert into classes values('$class')";
			echo $query;
			dbDo($query);
		}
		
		//Delete Class
		//Also deletes associated students, projects, images, comments and ratings.
		
		//!!!!!Delete class still needs to 
		
		if(isset($_POST['delete_class']) and $_POST['class'] != 'none'){
			$class = $_POST['class'];
			//Set longer wait time, all deletes could take a while. 
			set_time_limit(100);
			//Delete projects associated with class
			$result = dbGet("select projectID from projects where class='$class'");
			while($id = mysql_fetch_assoc($result)){
				delete_project($id['projectID']);
			}
			//Get list of users from class
			for($i=1;$i<=MAXIMUM_CLASSES;$i++){
				if($i==1){
					$access = 'access';
				}
				else{
					$access = "access$i";
				}
				$users = dbGet("select email from users where $access = '$class'");
				while($email = mysql_fetch_assoc($users)){
					//get associated imageID's connected to each user
					remove_associated_to_user_and_class($email['email'],$class);
				}
			}
			//Delete class. Save till end in case script doesn't finish.
			dbDo("delete from classes where class='$class'");
		}
		
		//Delete Student
		//Also deletes associated images, commments, and ratings.
		if(isset($_POST['delete_student'])){
			remove_associated_to_user($_POST['student_username']);
		}
		
		/////////////////////////////////
		//FORWARD TO CURRENT/OPENING PAGE
		header('Location: teacherhome.php');
	}
	////////////////////////////////////////////////////////////
	//STUDENT PROCESSES/////////////////////////////////////////
	////////////////////////////////////////////////////////////
	else{
		//PROSESS REQUESTS SENT TO INDEX.PHP
		//Delete a personal picture
		if(isset($_POST['delete_image'])){
			delete_image($_POST['imageID']);
		}
		//Issue a report on a comment
		/*
		if(isset($_POST['report_comment'])){
		$query = "update comments set report=1 where imageID=$_POST[picture] and author='$_POST[author]'";
		dbDo($query);
		}*/
		
		//Upload Images, currently only jpg support.
		if(isset($_FILES['file']) and $_POST['project'] != 'none'){
			set_time_limit(100);
			$owner = $_SESSION['username'];
			if($_FILES['file']['type'] == "application/x-zip-compressed"){
				//Manage Zip files
				//Server Must have max-upload size in php.ini adjusted to allow admin to upload full class files
				$zip = new ZipArchive;
				$zip->open($_FILES['file']['tmp_name']);
				$zip->extractTo(ini_get('upload_tmp_dir'));
				//$project = $_POST['project'];
				for($i = 0;$i < $zip->numFiles; $i++){
					$name = '/'.$zip->getNameIndex($i);
					if(strpos(strtolower($name),'.jpg') != false){
						$image = addslashes(file_get_contents(ini_get('upload_tmp_dir').$name));
						dbDo("insert into images (owner,data,projectID) values ('$owner','$image',$_POST[project])");
						//Grab imageID from creation and make new field for ratings in ratings table.
						$imageID = mysql_insert_id();
						create_rating_for_image($imageID,$_POST['project']);
					}
					unlink(ini_get('upload_tmp_dir').$name);
				}
			}
			else if($_FILES['file']['type'] == "image/jpeg"){
				//Upload Image file, current support only jpeg
				$image = addslashes(file_get_contents($_FILES['file']['tmp_name']));
				dbDo("insert into images (owner,data,projectID) values ('$owner','$image',$_POST[project])");
				//Grab imageID from creation and make new field for ratings in ratings table.
				$imageID = mysql_insert_id();
				create_rating_for_image($imageID,$_POST['project']);
			}
		}
		
		/////////////////////////////////
		//FORWARD TO CURRENT/OPENING PAGE
		header('Location: studenthome.php');
	}
}

//FAILED AT LOGGING IN
if(isset($_POST['username']) and isset($_POST['password'])){
	echo "<div id='warning'>Incorrect Username and/or Password</div>";
}

//VIEW LOGIN PAGE.
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type"text/css" href="photography.css">
		<title>Login</title>
		<!--Icon place holder until we find an super awesome icon-->
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<script src="photography.js"></script>
	</head>
	<body>
		<header id="logo">
		</header>
		<form action='index.php' method='POST'>
		<div class="menubar">
		<div id="inputBoxes">
			<input type="username" name="username" placeholder="Email"/><br>
			<input type="password" name="password" placeholder="Password"/><br>
		</div>
		<input class="submitButton" type="submit" name="Login" value="Login">
			<a id="login" href="createuser.php">Create New User Account</a>
			<p id="login" onclick="forgotPassword()">Forgot password</p>
		</form>
		</div>
		<footer id="footerLogin">
		<small><i><p>Copyright &copy; 2014 </p></i></small>
		</footer>
	</body>
</html>
