<?php
	require_once("secure.php");
	if(isset($_GET['imageId'])){
		include_once('functions.php');
		$data=get_comments_ratings(addslashes($_GET['imageId']));
		$comments = $data[0];
		$ratings = $data[1];
		echo "<h2>Ratings</h2><hr>";
		echo "<div id='ratings'>";
		foreach($ratings as $rat){
			echo "<div class='rating'>";
			echo "<h3>$rat[question]</h3>";
			$total = 0;
			$possible = 0;
			echo "<ul>";
			for($i = 1;$i <=$rat['scale'];$i++){
				$rating = $rat['v'.$i];
				if(empty($rating)){
					echo "<li>$i: 0</li>";
				}
				else{
					echo "<li>$i: $rating</li>";
					$total = $total + ($i-1)*$rating; //-1 so that a vote of '1' is worth 0 points, making it possible to have 0%.
					$possible = $possible + ($rat['scale']-1)*$rating; //a rating of '10' is worth 9 points. 
				}
			}
			if($possible == 0){
				echo "<li>Total: 0%</li>";
			}
			else{
				//subtracting from 100%, a 1 is going to be the highest vote, this will reverse it.
				echo "<li>Total: ".(string)(100-round(($total/$possible)*100,2))."%</li>"; 
			}
			echo "</ul>";
			echo "</div>";
		}
		echo "</div>";
		echo "<h2>Comments</h2><hr>";
		echo "<div id='comments'>";
		foreach($comments as $com){
			$name = email_to_username($com['author'],true);	
			echo "<div class='comment'>";
			echo "<h3>$name[username]<div id='avatar' style='float:right;margin-left:15px;'><img src='avatar.php?idnum=$name[idnum]'></div></h3>";
			echo "<p>$com[comment]</p>";
			if($_SESSION['access']=='admin'){ //Admin Delete a bad comment. 
				echo "<form method='POST' action='index.php'>";
				echo "<input type='hidden' name='author' value='$com[author]'>";
				echo "<input type='hidden' name='picture' value='$_GET[imageId]'>";
				echo "<input type='submit' name='delete_comment' value='Delete Comment'>";
				echo "</form>";
			}
			else{ //Student Report a bad comment. 
				/*echo "<form method='POST' action='index.php'>";
				echo "<input type='hidden' name='author' value='$com[author]'>";
				echo "<input type='hidden' name='picture' value='$_GET[imageId]'>";
				echo "<input type='submit' name='report_comment' value='Report Comment'>";
				echo "</form>";*///Need To create place for Teacher to view reported comments. 
			}
			echo "</div>";
		}
		echo "</div>";
		
	}
	
?>