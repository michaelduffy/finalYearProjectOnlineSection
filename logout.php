<?php
	if(isset($_COOKIE["cookieUsername"]))
	{
	//  $loggedUser=$_COOKIE["cookieUsername"];
	  // print($loggedUser);
	  setcookie("cookieUsername",null);
	   setcookie("cookieAthId",null);
	}
	header("Location: homePage.php");
?>