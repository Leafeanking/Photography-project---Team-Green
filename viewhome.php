<?php
require_once("secure.php");
require_once("functions.php");

$data = list_viewable($_SESSION['username'],$_SESSION['access']);
//student returns an array(array(projectID,theme,<img>coverPhoto))
	$class = '';
	foreach($data as $folder){
		//folder[3] will only exist for admin.
		//folder[3] contains the class that the project belongs to.
		//it will echo a header for each class when a new class ownership
		//appears in the list. The list is retrieved in order by class
		//from the function 'list_viewable()'.
		if($_SESSION['access'] == 'admin' and $folder[3] != $class){
			$class = $folder[3];
			echo "<div class='clear'><h2>$class</h2></div>";
		}
		echo "<div class='folder'>";
		if($_SESSION['access'] == 'admin'){//teacher View
			echo "<h2><a href=teacherhome.php?folder=$folder[0]>$folder[1]</a></h2>";
			echo "<div class='icon'><a href=teacherhome.php?folder=$folder[0]>$folder[2]</a></div>";
			echo "<a href=teacherhome.php?folder=$folder[0]><img src='Folder-icon.png' alt='blue folder'></a>";
		}
		else{//student View
			echo "<h2><a href=studenthome.php?folder=$folder[0]>$folder[1]</a></h2>";
			echo "<div class='icon'><a href=studenthome.php?folder=$folder[0]>$folder[2]</a></div>";
			echo "<a href=studenthome.php?folder=$folder[0]><img src='Folder-icon.png' alt='blue folder'></a>";
		}

		echo "</div>";
	}
echo "<div class='clear'></div>";
?>