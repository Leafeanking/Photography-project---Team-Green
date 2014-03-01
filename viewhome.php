<?php
require_once("secure.php");
require_once("functions.php");
$data = list_viewable($_SESSION['username'],$_SESSION['access']);
	foreach($data as $folder){
		echo "<div class='folder'>";
		echo "<h2><a href=studenthome.php?folder=$folder[0]>$folder[1]</a></h2>";
		echo "<div class='icon'><a href=studenthome.php?folder=$folder[0]>$folder[2]</a></div>";
		echo "<a href=studenthome.php?folder=$folder[0]><img src='Folder-icon.png' alt='blue folder'></a>";
		echo "</div>";
	}
echo "<div class='clear'></div>";
?>