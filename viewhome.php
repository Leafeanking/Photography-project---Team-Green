<?php
require_once("secure.php");
require_once("functions.php");

////////////////////////////////////////////////
//Following added for multi-class compatibility.
//Only affects a student user.
//Admin user passes through script once only. 

$data = list_classes_in($_SESSION['username']);
foreach($data as $access){
	
//////////////////////////////////////////////////
//Original script follows.
	//This should only run for studens. Admin views class tags another way. 
	if($access != 'admin'){
		echo "<h2>$access</h2>";
	}
	
	$data = list_viewable($_SESSION['username'],$access);
	//student returns an array(array(projectID,theme,<img>coverPhoto))
		$class = '';
		foreach($data as $folder){
			
			if($_SESSION['access'] == 'admin' and $folder[3] != $class){
				//Admin Header.
				//folder[3] will only exist for admin.
				//folder[3] contains the class that the project belongs to.
				//it will echo a header for each class when a new class ownership
				//appears in the list. The list is retrieved in order by class
				//from the function 'list_viewable()'.
				$class = $folder[3];
				echo "<div class='clear'><h2>$class</h2></div>";
			}
			echo "<div class='folder'>";
			if($_SESSION['access'] == 'admin'){//teacher View
				echo "<h2><a href=teacherhome.php?folder=$folder[0]>$folder[1]</a></h2>";
				echo "<div class='icon'><a href=teacherhome.php?folder=$folder[0]>$folder[2]</a></div>";
			}
			else{//student View
				echo "<h2><a href=studenthome.php?folder=$folder[0]>$folder[1]</a></h2>";
				echo "<div class='icon'><a href=studenthome.php?folder=$folder[0]>$folder[2]</a></div>";
				
			}

			echo "</div>";
		}
	echo "<div class='clear'></div>";
}
?>