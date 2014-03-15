
//Join button function in studenthome
function Join() {
	var access=prompt("Enter access code to join the discussion");
	//if they typed in the right access code, they jump to the discussion page
	if(access=="true"){
		window.location="sessionpage.php";
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
