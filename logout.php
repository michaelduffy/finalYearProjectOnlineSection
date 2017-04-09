<?php
	if(isset($_COOKIE["cookieUsername"]))//if user logged in
	{
	   //clear cookies
	   setcookie("cookieUsername",null); 
	   setcookie("cookieAthId",null);
	}
	header("Location: homePage.php"); //go to home page 
?>