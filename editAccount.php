<?php
	if(isset($_COOKIE["cookieAthId"]))
	{
	  
		$athId=$_COOKIE["cookieAthId"];
		print("id= $athId");
		print("</br>");
		$firstName = $_POST['txtFirstName'];
		$lastName = $_POST['txtLastName'];
		print("last name = $lastName");
		$add1 = $_POST['txtAddress1'];
		$add2 = $_POST['txtAddress2'];
		$add3 = $_POST['txtAddress3'];
		$county = $_POST['county'];
		$gender = $_POST['gender'];
		$dob = $_POST['txtDob'];
		$phone = $_POST['txtPhone'];
		$email = $_POST['txtEmail'];
		$password = $_POST['txtPassword'];
		
		//print($firstName."--".$lastName."--".$add1."--".$add2."--".$add3."--".$county."--".$gender."--".$dob."--".$phone."--".$email."--".$password);
		
		$connection=mysqli_connect("localhost","root",""); //("localhost","d567687_michael","marybary59");
			
		mysqli_select_db($connection,"project_database");
			
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
               
		mysqli_close($connection);
		header("Location: displayAccount.php");

	}
?>