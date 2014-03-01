<header>
	<link rel="stylesheet" type="text/css" href="teststyle.css" />
</header>

<?php
include_once("functions.php");
echo "<p>";
echo "*******Add new project*******<br/>";
create_project('fun','make photos','do you like photos','asdf',2,'do you like art?','fdsa',3);
echo "</p><p>";
echo "*******Authenticate********<br/>";
print_r(authenticate('admin','password'));
echo "</p><p>";
echo "********List Viewable*******<br/>";
print_r(list_viewable('admin','admin'));
echo "</p><p>";

echo "********List Viewable Image Only*******<br/>";
$a = list_viewable('admin','admin');
echo "<div class='photo'>".$a[0][2]."</div>";
echo "</p>";
echo "</p><p>";
echo "********Access_personal_project*******<br/>";
print_r(access_personal_project('admin',1));
echo "</p><p>";
echo "********Get Comments Ratings*******<br/>";
print_r(get_comments_ratings(1));
echo "</p><p>";
echo "********Access_full_project***********<br/>";
print_r(access_full_project(1));
echo "</p>";
?>