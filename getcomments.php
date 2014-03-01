<?php
	if(isset($_GET['imageId'])){
		include_once('functions.php');
		$data=get_comments_ratings($_GET['imageId']);
		$comments = $data[0];
		$ratings = $data[1];
		echo "<div id='comments'>";
		foreach($comments as $com){
			echo "<div class='comment'>";
			echo "<h3>$com[author]</h3>";
			echo "<p>$com[comment]</p>";
			echo "</div>";
		}
		echo "</div>";
		echo "<div id='ratings'>";
		foreach($ratings as $rat){
			echo "<div class='rating'>";
			echo "<h3>$rat[question]</h3>";
			$total = 0;
			$possible = 0;
			echo "<ul>";
			for($i = 1;$i <=$rat['scale'];$i++){
				if(empty($rat[$i])){
					echo "<li>$i: 0</li>";
				}
				else{
					echo "<li>$i: $rat[$i]</li>";
					$total = $total + $i*$rat[$i];
					$possible = $possible + $rat['scale']*$rat[$i];
				}
			}	
			echo "<li>Total: ".(string)(($total/$possible)*100)."%</li>";
			echo "</ul>";
			echo "</div>";
		}
		echo "</div>";
	}
	
?>