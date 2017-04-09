<html>
<?php require_once('topNav.html'); ?>
<body>
<!--create form and table to house it -->
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

if(isset ($_POST['btnSubmit'])) //if user submits details
{
        $enteredEmail=$_POST['txtUsername'];
	$enteredPassword=$_POST['txtPassword'];
	$validUser=false;
	$athId="";
	$dob="";
	$email="";
	$pass="";
	$athFirstName="";
	
	//getting DB connection
	$connection=mysqli_connect("localhost","root","");
	mysqli_select_db($connection,"project_database");

	$result = mysqli_query($connection,"select athlete_id, ath_first_name, date_of_birth, email, password from series_athlete");

	while($row = mysqli_fetch_array($result))
		{
			$email=$row['email'];
			$pass=$row['password'];
		    
			if(($email==$enteredEmail) && ($pass==$enteredPassword)) //checking entered email and password against those in database
			{	
			       //when match found get the required details and break from loop
				$athId=$row['athlete_id'];
				$dob=$row['date_of_birth'];
				$athFirstName= $row['ath_first_name'];
				$validUser=True;
				break;
			}
		    
		}
		
		mysqli_close($connection);
		
		if($validUser == true) //creating cookies if user details are valid
		{
			$cookieName="cookieUsername";
			$cookieValue=$athFirstName;
			$cookieExpire=time()+(60*60*24*1); //1day
			setcookie($cookieName,$cookieValue,$cookieExpire);
			
			$cookieName="cookieAthId";
			$cookieValue=$athId;
			$cookieExpire=time()+(60*60*24*1); //1day
			setcookie($cookieName,$cookieValue,$cookieExpire);
			header("Location: resultsPage.php");
		}
		else
		{
			print("<script language='javascript'>");		
				print('alert("Incorrect details entered!")');//informing user if details entered are not valid
			print("</script>");
		} 			
}

?>