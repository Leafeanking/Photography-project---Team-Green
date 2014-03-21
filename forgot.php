<?php
if(isset($_GET['email'])){
	$email = addslashes($_GET['email']);
	$results = dbGet("select * from users where email = '$email'");
	if(mysql_num_rows($results) >= 1){
		srand(time());
		$pass = "iforgot".((string)rand())."dsu";
		dbDo("update users set password = '$pass' where email = '$email'");
		mail($_GET['email'],"Photography: You Forgot",
			"Your password is: $pass \n Please change it quickly.",
			"From: DSU_Photography@noreply\n"
	}
}

?>