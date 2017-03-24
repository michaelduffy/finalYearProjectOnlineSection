<?php

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
	
	//print($firstName."--".$lastName."--".$add1."--".$add2."--".$add3."--".$county."--".$gender."--".$dob."--".$phone."--".$email."--".$password);
	
	$connection=mysqli_connect("localhost","root",""); //("localhost","d567687_michael","marybary59");
		
	mysqli_select_db($connection,"project_database");
		
	$result = mysqli_query($connection,"select athlete_id, ath_first_name, ath_last_name, phone_num from series_athlete");
	
	$newAthlete=true;
	$athName="";
	$athPhone=0;
	$highestId=1;
	$currentId=0;
	$newId=0;
	
	while($row = mysqli_fetch_array($result))
	{
		$athFirstName=$row['ath_first_name'];
		$athLastName=$row['ath_last_name'];
		$athPhone=$row['phone_num'];
		if( ($athFirstName==$firstName) &&($athLastName==$lastName) && ($athPhone==$phone) )  //check athlete is not already in database
		{
			$newAthlete=false;
		}
		
		$currentId=$row['athlete_id'];
		if($currentId > $highestId)
		{
			$highestId = $currentId;
		}
	}
	
	$newId = $highestId+1;
		
	if($newAthlete)
	{		
		$addSuccess = mysqli_query($connection,"INSERT INTO series_athlete VALUES($newId, '$firstName', '$lastName', '$add1', '$add2', '$add3', '$county', '$gender', '$dob', '$phone', '$email', '$password')");
		if($addSuccess == true)
		{
			header("Location: getAthleteDetails.php?addSuccess=pass&id=$newId&firstName=$firstName&lastName=$lastName&add1=$add1&add2=$add2&add3=$add3&county=$county&gender=$gender&dob=$dob&phone=$phone&email=$email&password=$password");  
		}
		else
		{
		     header("Location: getAthleteDetails.php?addSuccess=fail");		 
		}
	}
	else
	{
	        header("Location: getAthleteDetails.php?addSuccess=invalid");		
	}  
        mysqli_close($connection);
?>