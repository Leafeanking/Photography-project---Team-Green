
//Join button function in studenthome and teacherhome
function Join() {
	var access=prompt("Enter access code to join the session");
	//if they typed in the right access code, they jump to the session page
	if(access=="true"){
		window.location="sessionpage.php";
	}
	else{
		alert("There is no session going on with that access code.");
	}
}

//Forgot password link in homepage
function forgotPassword()
{
	var email=prompt("Forgot password? Please enter your Dmail user name.");
	var result = send_password(email);
	if(result == 'true')
	{
		var ok=confirm("Your password has been sent to your email.");
		
	}
	else{
		var ok=confirm("Your email does not exist in our system.");
	}
}
