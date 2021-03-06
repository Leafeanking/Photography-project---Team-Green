<?php
/*
Documentation:
Make sure image.php is available to the script when using functions that return an image.

*****While using functions such as list_viewable(...) and displaying the image, the best way of doing so is example:
$a = list_viewable('admin','admin');
echo "<div class='photo'>".$a[0][2]."</div>";

stylesheet{
	.photo img{
		width:400px;
	}
}

*/
define('MAXIMUM_QUESTIONS',20);
define('DEFAULT_IMG',"<image src='picture_icon.png'>"); 
define('MAXIMUM_CLASSES',10);
define('SERVER','68.178.216.155');
define('SERVER_USER','dsuphotography');
define('SERVER_PASSWORD','P1ctur3#1000');
//////////////////////////////////////////////////////////////////////////////
//Full Scope Functions////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
function dbGet($query){
	//used to access database for any query line, get, insert, update.
	$con = mysql_connect(SERVER,SERVER_USER,SERVER_PASSWORD) or die(mysql_error());
	mysql_select_db('dsuphotography',$con);
	$result = mysql_query($query)  or die ("Fail: ".$query);
	return $result;
}

function dbDo_noErr($query){
	//Does not report error on query. Used if multiple submits of form
	//is possible, and still want to submit other valid parts of form.
	//used to access database for any query line, get, insert, update.
	$con = mysql_connect(SERVER,SERVER_USER,SERVER_PASSWORD) or die(mysql_error());
	mysql_select_db('dsuphotography',$con);
	$result = mysql_query($query);
	return $result;
}

function dbDo($query){
	return dbGet($query);
}

function authenticate($user,$pass){
	if(!isset($_SESSION)){
		session_start();
	}
	//check if user is authentic, return false if not, return access if they are.
	//Outer functions responsibility to set $_Session['user'], if user is correct.
	$query = "SELECT * FROM users where email = '$user' and password = '$pass'";
	$results = dbGet($query);
	if(mysql_num_rows($results)!=0){
		$val = mysql_fetch_assoc($results);
		$_SESSION['access'] = $val['access'];
		//Set access for multiple classes if student is in multiple classes.
		for($i = 2; $i <= MAXIMUM_CLASSES;$i++){
			if($val["access$i"]!= NULL){
				$_SESSION["access$i"] = $val["access$i"];
			}
			else if(isset($_SESSION["access$i"])){
				//Remove access from session if exists in session variable, but user no longer 
				//has that many classes, used for re-initializing session data for what classes
				//user belongs to. Used when updating profile.
				unset($_SESSION["access$i"]);
			}
		}
		return true;
	}
	return false;
}

function list_classes_in($user){
	//Returns a list of classes/access that the student has
	//Returns as array(class,class,class...);
	$query = "select * from users where email='$user'";
	$results = dbGet($query);
	$return = array();
	$data = mysql_fetch_assoc($results);
	for($i=1;$i<=MAXIMUM_CLASSES;$i++){
		if($i==1){
			$access = 'access';
		}
		else{
			$access = "access$i";
		}
		if($data[$access] != '' and $data[$access] != NULL){
			array_push($return,$data[$access]);
		}
	}
	return $return;
}

function get_theme($projectID){
	$query = "select theme from projects where projectID = $projectID";
	$results = dbGet($query);
	$data = mysql_fetch_row($results);
	return $data[0];
}


function username_from_email($email){
	$results = dbGet("select username from users where email = '$email'");
	if(mysql_num_rows($results) != 0){
		$data = mysql_fetch_assoc($results);
		return $data['username'];
	}
	return "Name";
}

function list_viewable($user,$access){
	//checks project list for projects that a class($access) belongs to, and then
	//grabs first photo from corresponding student 'folders'
	//student returns an array(array(projectID,theme,<img>coverPhoto))
	if($access != 'admin'){ //Student View
		$query = "select projectID, theme from projects where class = '$access'";
		$results = dbGet($query);
		$return = array();
		while($item = mysql_fetch_assoc($results)){
			$query = "select imageID from images where owner = '$user' and projectID = '$item[projectID]' LIMIT 0,1";
			$resultsImage = dbGet($query);
			$data = mysql_fetch_row($resultsImage);
			if($data){
				$picture = "<img src='image.php?imageID=$data[0]'/>";
				}
			else{
				$picture = DEFAULT_IMG;
			}
			$packet = array($item['projectID'],$item['theme'],$picture);
			array_push($return,$packet); 
		}
		return $return;
	}
	//Teacher returns an array(array(projectID,theme,<img>coverPhoto,class))
	else if($access == 'admin'){ //Teacher View
		$query = "select projectID, theme, class from projects order by class";
		$results = dbGet($query);
		$return = array();
		while($item = mysql_fetch_assoc($results)){
			$query = "select imageID from images where projectID = '$item[projectID]' LIMIT 0,1";
			$resultsImage = dbGet($query);
			$data = mysql_fetch_row($resultsImage);
			if($data){
				$picture = "<img src='image.php?imageID=$data[0]'/>";
				}
			else{
				$picture = DEFAULT_IMG;
			}
			$packet = array($item['projectID'],$item['theme'],$picture,$item['class']);
			array_push($return,$packet); 
		}
		return $return;
	}
}

function list_viewable_no_pic($user,$access){
	//checks project list for projects that a class($access) belongs to, and then
	//grabs first photo from corresponding student 'folders'
	//returns an array(array(projectID,theme)) 
	if($access != 'admin'){ //Student View
		$query = "select projectID, theme from projects where class = '$access'";
		$results = dbGet($query);
		$return = array();
		while($item = mysql_fetch_assoc($results)){
			$packet = array($item['projectID'],$item['theme']);
			array_push($return,$packet); 
		}
		return $return;
	}
	//returns an array of all projects. 
	else if($access == 'admin'){ //Teacher View
		$query = "select projectID, theme from projects";
		$results = dbGet($query);
		$return = array();
		while($item = mysql_fetch_assoc($results)){
			$packet = array($item['projectID'],$item['theme']);
			array_push($return,$packet); 
		}
		return $return;
	}
}

//Create Rating field for newly made image
function create_rating_for_image($imageID,$projectID){
	$results = dbGet("select * from projects where projectID = '$projectID'");
	$project = mysql_fetch_assoc($results);
	//create a rating field for each question that exists in a project for the selected project and image. 
	for($i = 1;$i<=20;$i++){
		$question = $project['q'.(string)$i];
		$questionID = $project['q'.(string)$i.'ID'];
		$scale = $project['scale'.(string)$i];
		if($question != NULL and $questionID != NULL and $scale != NULL){
			$query="insert into ratings (imageID,questionID,question,scale) values ('$imageID','$questionID','$question','$scale')";
			dbDo($query);
		}
	}
}

//If include_id is true, returns as an array, else returns as a single value.
//Had to do this way in order to make sure other uses of this function don't break. 
function email_to_username($email,$include_id=false){
	$result = dbGet("select username,idnum from users where email = '$email'");
	if(mysql_num_rows($result) != 0){
		$data = mysql_fetch_assoc($result);
		if($include_id){
			$name = $data;
		}
		else{
			$name = $data['username'];
		}
	}
	else{
		$len = strpos($email,'@');
		if($len != false){
			$name = substr($email,0,$len);
		}
		else{
			$name = $email;
		}
		if($include_id){
			$name = array('username'=>$name,'idnum'=>'');
		}
	}
	return $name;
}

function delete_image($id){
	$query = "delete from images where imageID = '$id'";
	$query2 = "delete from comments where imageID = '$id'";
	$query3 = "delete from ratings where imageID = '$id'";
	dbDo($query);
	dbDo($query2);
	dbDo($query3);
}

///////////////////////////////////////////////////////////////////////
//Admin Functions//////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

function delete_project($projID){
	$results = dbGet("select imageID from images where projectID = $projID");
	while($id = mysql_fetch_assoc($results)){
		delete_image($id['imageID']);
	}
	dbDo("delete from projects where projectID = $projID");
}


function get_classes(){
//Returns Array of ClassID's 
$query = "select * from classes";
$results = dbGet($query);
$classes = array();
while($data = mysql_fetch_assoc($results)){
		array_push($classes,$data['class']);
	}
return $classes;
}

function access_full_project($projectID){
	//For accessing specific folder and reviewing personal photos
	//Returns an array(array(imageID,<img>,owner),array(imageID,<img>,owner))
	$query = "select imageID, owner from images where projectID = '$projectID' order by owner ASC";
	$results = dbGet($query);
	$return = array();
	while($item = mysql_fetch_assoc($results)){
		$picture = "<img src='image.php?imageID=$item[imageID]'/>";
		$packet = array($item['imageID'],$picture,$item['owner']);
		array_push($return,$packet);
	}
	return $return;
}

function remove_user_completely($email){
	$result = dbGet("select imageID from images where owner ='$email'");
	while($data = mysql_fetch_assoc($result)){
		//deletes image, commments, and ratings tied to image. 
		delete_image($data['imageID']);
	}
	dbDo("delete from users where email = '$email'");
}

function remove_associated_to_user_and_class($email,$class){
	//Deletes all items associated with a user and class together.
	//If the user no longer has any associations with any classes,
	//The user is then deleted.

	$projects_of_class = list_viewable_no_pic($email,$class);
	foreach($projects_of_class as $proj){
		$assocImages = dbGet("select imageID from images where owner = '$email' and projectID = $proj[0]");
		while($image = mysql_fetch_assoc($assocImages)){
			//Delete everything from comments, ratings, and images associated to each imageID.
			delete_image($image['imageID']);
		}
	}
	//delete user.
	$result = dbGet("select * from users where email = '$email'");
	$data = mysql_fetch_assoc($result);
	$flag = false; //Flag to keep track if the certain class has been reached;
	
	//A one time bubble sort. Only has to go up it once. 
	//Finds the class that needs to be removed, then copies the values
	//above it downward. 
	for($i=1;$i<=MAXIMUM_CLASSES;$i++){
		if($i==1){
			$access = 'access';
		}
		else{
			$access = "access$i";
		}
		$next = $i+1;
		$next = "access$next";
		
		//Code specific to the last item. 
		if($i == MAXIMUM_CLASSES){
			//accessMAXIMUM_CLASSES will always go to null when a class is removed, since the list goes downward.
			dbDo("update users set $access = NULL where email = '$email'");
			break; //accessMAXIMUM_CLASSES must break, it does not have a 'next' so the following code will not work for it. 
		}
		
		//If the class has been found, take all other classes/access' and move them down the list. 
		if($flag){
			if($data[$next] == ''){
				dbDo("update users set $access = NULL where email='$email'");
			}
			else{
				dbDo("update users set $access = '$data[$next]' where email='$email'");
			}
		}
		else if($data[$access] == $class){
			//If the current access field is the class being removed, remove class;
			if($data[$next] == ''){
				dbDo("update users set $access = NULL where email='$email'");
			}
			else{
				dbDo("update users set $access = '$data[$next]' where email='$email'");
			}
			$flag = true;
		}
	}
	$result = dbGet("select access from users where email = '$email'");
	$data = mysql_fetch_assoc($result);
	//If not tied to any classes, delete user. 
	if($data['access'] === NULL or $data['access'] == ''){
		remove_user_completely($email);
	}
}

///////////////////////////////////////////////////////////////////////
//Student Functions////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
function access_personal_project($user,$projectID){
	//For accessing specific folder and reviewing personal photos
	//Returns an array(array(imageID,<img>),array(imageID,<img>))
	$query = "select imageID from images where owner = '$user' and projectID = '$projectID'";
	$results = dbGet($query);
	$return = array();
	while($item = mysql_fetch_assoc($results)){
		$picture = "<img src='image.php?imageID=$item[imageID]'/>";
		$packet = array($item['imageID'],$picture);
		array_push($return,$packet);
	}
	return $return;
}

function get_comments_ratings($imageID){
	//Gets comments and ratings for an individual image.
	//Returns as array(
	//				array(array(author,comment),array(author,comment)),
	//				array(array(imageID, questionID, question,scale,1,2,3,4,5,6,7,8,9,10),array(imageID, questionID, question,scale,1,2,3,4,5,6,7,8,9,10))
	//				)
	$query = "select author, comment from comments where imageID = '$imageID'";
	$results = dbGet($query);
	$return = array();
	$comments = array();
	$ratings = array();
	while($item = mysql_fetch_assoc($results)){
		array_push($comments,$item);
	}
	$query = "select * from ratings where imageID = '$imageID'";
	$results = dbGet($query);
	while($item = mysql_fetch_assoc($results)){
		array_push($ratings,$item);
	}
	array_push($return,$comments);
	array_push($return,$ratings);
	return $return;
}


?>