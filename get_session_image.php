<?php
require_once("secure.php");
require_once("functions.php");

//echo form data for voting. 
if(isset($_GET['session']) and isset($_GET['votes'])){
	$session = $_GET['session'];
	$IDresult = dbGet("select imageID from session where accessCode = '$session'");
	if(mysql_num_rows($IDresult) != 0){
		$IDdata = mysql_fetch_assoc($IDresult);
		$image = $IDdata['imageID'];
		
		$projresult = dbGet("select projectID from images where imageID = $image");
		$projdata = mysql_fetch_assoc($projresult);
		$project = $projdata['projectID'];
		
		$layoutresult = dbGet("select * from projects where projectID = $project");
		$layout = mysql_fetch_assoc($layoutresult);
		
		echo "<h3>$layout[theme]</h3>";
		echo "<br/>";
		for($i=1;$i<=MAXIMUM_QUESTIONS;$i++){
			$question = $layout['q'.$i];
			$qid = $layout['q'.$i.'ID'];
			$qidSub = '"'.$qid.'"';
			$scale = $layout['scale'.$i];
			$start = 1;			
			if($question != '' and $qid != '' and $scale != ''){
				echo "<h4>$question</h4>";
				echo "<input id='slider$qid' type='range' name='$qid' max='$scale' min=1 value=$start onchange='update_selected_value($qidSub)'><span id='$qid'>$start</span>";
			}
			else{
				break;
			}
		}
		
	}
}
//echo image.
else if(isset($_GET['session']) and isset($_GET['pull'])){
	$session = $_GET['session'];
	$result = dbGet("select * from session where accessCode = '$session'");
	if(mysql_num_rows($result) != 0){
		$data = mysql_fetch_assoc($result);
		//Send Current Image selected by teacher
		echo "<img src='image.php?imageID=$data[imageID]'/>";
	}
	else{
		//Session doesn't exist, send text that image does not exist.
		echo 'Image Not Found';
	}
}
//echo image id number.
else if(isset($_GET['session'])){
	$session = $_GET['session'];
	$result = dbGet("select * from session where accessCode = '$session'");
	if(mysql_num_rows($result) != 0){
		$data = mysql_fetch_assoc($result);
		//Send Current ImageID selected by teacher
		echo $data['imageID'];
	}
	else{
		//Session doesn't exist, send -1 for no pic.
		echo '-1';
	}
}
else{
	//No Session sent
	exit();
}


?>