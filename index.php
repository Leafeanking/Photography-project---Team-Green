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
	$_SESSION['access'] = authenticate($_POST['username'],$_POST['password']);
}

//FORWARD WHEN LOGGING IN, OR ALREADY LOGGING IN AND SUBMITTING POST FORM DATA
if(isset($_SESSION['access']) and $_SESSION['access'] != false){
	//Manage user view for admin or student
	if(isset($_GET['folder'])){
		$_SESSION['view'] = $_GET['folder'];
	}
	//ADMIN PROCESSES///////////////////////////////////////////
	if($_SESSION['access'] == 'admin'){
		//PROSESS REQUESTS SENT TO INDEX.PHP
		//Delete a comment
		if(isset($_POST['delete_comment'])){
		$query = "delete from comments where imageID='$_POST[picture]' and author = '$_POST[author]'";
		dbDo($query);
		}
		//Delete a Image
		if(isset($_POST['delete_image'])){
		$query = "delete from images where imageID = '$_POST[imageID]'";
		$query2 = "delete from comments where imageID = '$_POST[imageID]'";
		$query3 = "delete from ratings where imageID = '$_POST[imageID]'";
		dbDo($query);
		dbDo($query2);
		dbDo($query3);
		}
		
		if(isset($_POST['create_project']) and isset($_POST['scale1']) and $_POST['scale1']!='none'){
			srand(time());
			function create_id($project,$question){
				$id = (string)(rand()*rand())%999999999;
				if(strlen($project) > 3){
					$id = substr(strrev($project),0,3).$id;
				}
				if(strlen($question) > 3 ){
					$id = $id.$question;
				}
				return $id;
			}
			$class = $_POST['class'];
			$theme = str_replace("'","`",$_POST['theme']);
			$question1 = str_replace("'","`",$_POST['question1']);
			$q1ID = create_id($theme,$question1);
			dbDo("insert into projects (class,theme,q1,q1ID,scale1) values ('$class','$theme','$question1','$q1ID','$_POST[scale1]')");
			$projectID = mysql_insert_id();
			for($i = 2;$i <= 20;$i++){
				if($_POST['question'.$i] != '' and $_POST['scale'.$i] != ''){
					$question = str_replace("'","`",$_POST['question'.$i]);
					$scale = $_POST['scale'.$i];
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
		
		
		//FORWARD TO CURRENT/OPENING PAGE
		header('Location: teacherhome.php');
	}
	//STUDENT PROCESSES/////////////////////////////////////////
	else{
		//PROSESS REQUESTS SENT TO INDEX.PHP
		//Delete a personal picture
		if(isset($_POST['delete_image'])){
		$query = "delete from images where imageID = '$_POST[imageID]'";
		$query2 = "delete from comments where imageID = '$_POST[imageID]'";
		$query3 = "delete from ratings where imageID = '$_POST[imageID]'";
		dbDo($query);
		dbDo($query2);
		dbDo($query3);
		}
		//Issue a report on a comment
		if(isset($_POST['report_comment'])){
		$query = "update comments set report=1 where imageID=$_POST[picture] and author='$_POST[author]'";
		dbDo($query);
		}
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
					}
					unlink(ini_get('upload_tmp_dir').$name);
				}
			}
			else if($_FILES['file']['type'] == "image/jpeg"){
				//Upload Image file, current support only jpeg
				$image = addslashes(file_get_contents($_FILES['file']['tmp_name']));
				dbDo("insert into images (owner,data,projectID) values ('$owner','$image',$_POST[project])");
			}
		}
		
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
		<h1>Photography</h1>
		<script src="javafunctions.js"></script>
		</header>
		<form action='index.php' method='POST'>
		<div class="menubar">
			<div class="inputBoxes">
			<input type="username" name="username" placeholder="Username"/><br>
			<input type="password" name="password" placeholder="Password"/>
			</div>
			<div class="submitButton">
			<input type="submit" name = "Login">
			</div>
			<a class="center_link" href="createuser.html">Create New User Account</a>
			<p onclick="forgotPassword()">Forgot password</p>
		</form>
		</div>
		<footer>
		<small><i>Copyright &copy; 2014 <br></i></small>
		</footer>
	</body>
</html>
