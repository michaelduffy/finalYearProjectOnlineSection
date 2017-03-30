<?php
	if(isset($_COOKIE["cookieAthId"])) //checking if user is logged in
	{	 
		//getting edited details
		$athId=$_COOKIE["cookieAthId"];
		$firstName = $_POST['txtFirstName'];
		$lastName = $_POST['txtLastName'];
		$add1 = $_POST['txtAddress1'];
		$add2 = $_POST['txtAddress2'];
		$add3 = $_POST['txtAddress3'];
		$county = $_POST['county'];
		$gender = $_POST['gender'];
		$dob = $_POST['txtDob'];
		$phone = $_POST['txtPhone'];
		$email = $_POST['txtEmail'];
		$password = $_POST['txtPassword'];
		
		//getting db connection
		$connection=mysqli_connect("localhost","root",""); 			
		mysqli_select_db($connection,"project_database");
		
		//execute update
		mysqli_query($connection,"UPDATE series_athlete SET ath_first_name='$firstName', 
		ath_last_name='$lastName', 
		address_line_1='$add1', 
		address_line_2='$add2', 
		address_line_3='$add3',
		county='$county', 
		gender='$gender', 
		date_of_birth='$dob', 
		phone_num='$phone', 
		email='$email', 
		password='$password' WHERE athlete_id='$athId'");
               
		mysqli_close($connection); //close db connection
		header("Location: displayAccount.php"); //return to account info page
	}
?>