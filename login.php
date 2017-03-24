<html>
<?php require_once('topNav.html'); ?>
<body>

<form method="POST" action="login.php">
<br/><br/><br/>
<table border =2  ><caption><b>Enter Details Below</b></caption>    
<tr><td>Email:</td><td><input type="text" name="txtUsername" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="txtPassword" /></td></tr>
<tr><td></td><td><input type="submit" name="btnSubmit" value="Login"/></td></tr>
</table>
</form>
</body>
	
</html>

<?php

if(isset ($_POST['btnSubmit'])) //if(isset($_COOKIE['username']) )   //<input type="submit" name="submitBtn" value="Login"/>
{
        $enteredEmail=$_POST['txtUsername'];
	$enteredPassword=$_POST['txtPassword'];
	$validUser=false;
	$athId="";
	$dob="";
	$email="";
	$pass="";
	$athFirstName="";

		      

	$connection=mysqli_connect("localhost","root","");
		//print("test 1");
	mysqli_select_db($connection,"project_database");
		//print("test 2");
	$result = mysqli_query($connection,"select athlete_id, ath_first_name, date_of_birth, email, password from series_athlete");

	while($row = mysqli_fetch_array($result))
		{
			$email=$row['email'];
			$pass=$row['password'];
		    
			if(($email==$enteredEmail) && ($pass==$enteredPassword))
			{	
				$athId=$row['athlete_id'];
				$dob=$row['date_of_birth'];
				$athFirstName= $row['ath_first_name'];
				$validUser=True;
				break;
			}
		    
		}
		
		mysqli_close($connection);
		
		if($validUser == true)
		{
			//Print("You are a valid user");
			$cookieName="cookieUsername";
			$cookieValue=$athFirstName;
			$cookieExpire=time()+(60*60*24*1); //1day
			setcookie($cookieName,$cookieValue,$cookieExpire);
			
			$cookieName="cookieAthId";
			$cookieValue=$athId;
			$cookieExpire=time()+(60*60*24*1); //1day
			setcookie($cookieName,$cookieValue,$cookieExpire);
			//header("Location: homePage.php?email=$email&password=$password&athleteId=$athId&dob=$dob"); 
			header("Location: resultsPage.php");
		}
		else
		{
		print("<script language='javascript'>");
		
			print('alert("Incorrect details entered!")');
		print("</script>");
			
			//print('alert("Succe
			//header("Location: homePage.php");
		} 
		
	mysqli_close($connection);
	
}

?>