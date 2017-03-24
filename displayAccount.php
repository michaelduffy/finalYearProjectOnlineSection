<?php
	$athId="";
	require_once('topNav.html'); 
	if(isset($_COOKIE["cookieAthId"]))
	{
	  // $cookieSet=True;
		$athId=$_COOKIE["cookieAthId"];
		$athFirstName="";
		$athLastName="";
		$add1="";
		$add2="";
		$add3="";
		$county="";	
		$gender="";
		$dob="";
		$phoneNum="";
		$email="";
		$pass="";
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	
	print("<head>
			
			<script src='js/jquery-1.12.3.js'> </script>
			<link rel='stylesheet' type='text/css' href='js/jquery.datepick.css''>
			<script type='text/javascript' src='js/jquery.plugin.js'> </script>
			<script type='text/javascript' src='js/jquery.datepick.js'> </script>
			<script src='js/jQueryTest.js'> </script>
		 </head>");	
	

		      

	$connection=mysqli_connect("localhost","root","");
		//print("test 1");
	mysqli_select_db($connection,"project_database");
		//print("test 2");
	$result = mysqli_query($connection,"select ath_first_name, ath_last_name, address_line_1, address_line_2, address_line_3, county, gender, date_of_birth, phone_num, email, password from series_athlete where athlete_id = $athId");

	while($row = mysqli_fetch_array($result))
		{
			$athFirstName=$row['ath_first_name'];
			$athLastName=$row['ath_last_name'];
			$add1=$row['address_line_1'];
			$add2=$row['address_line_2'];
			$add3=$row['address_line_3'];
			$county=$row['county'];			
			$gender=$row['gender'];
			$dob=$row['date_of_birth'];
			$phoneNum=$row['phone_num'];
			$email=$row['email'];
			$pass=$row['password'];
		}
		
		mysqli_close($connection);
	}


	print("<table border=1> <caption><b>My Details</b></caption>");
						
				 $countyCombo = "<select name='county'> 
							<option value='$county' selected>$county</option>
							<option value='Donegal'>Donegal</option>
							<option value='Sligo'>Sligo</option>
							<option value='Fermanagh'>Fermanagh</option>
							<option value='Derry'>Derry</option>
							<option value='Tyrone'>Tyrone</option>
							<option value='Lietrim'>Lietrim</option>
							<option value='Mayo'>Mayo</option>
							<option value='Roscommon'>Roscommon</option>
						   </select>";
				if($gender == 'f')
				{				
					$genderRadio = "<input type='radio' name='gender' value='m' />Male <input type='radio' name='gender' value='f' checked> Female";
				}
				else
				{
					$genderRadio = "<input type='radio' name='gender' value='m' checked/>Male <input type='radio' name='gender' value='f'> Female";
				}
				
				 print("<form method='post' action='editAccount.php'>");
						
					   print("<tr><td align='right'>Race Series ID: </td><td><input type='input' size='50' name='txtFirstName' value ='$athId' required readonly/></td></tr>");
					   print("<tr><td align='right'>First Name: </td><td><input type='input' size='50' name='txtFirstName' value ='$athFirstName' required /></td></tr>");
					    print("<tr><td align='right'>Last Name: </td><td><input type='input' size='50' name='txtLastName' value ='$athLastName' required /></td></tr>");
					   print("<tr><td align='right'>Address line 1: </td><td><input type='input' size='50' name='txtAddress1' value ='$add1' id='id1'/></td></tr>");
					   print("<tr><td align='right'>Address line 2: </td><td><input type='input' size='50' name='txtAddress2' value ='$add2'/></td></tr>");
					   print("<tr><td align='right'>Address line 3: </td><td><input type='input' size='50' name='txtAddress3' value ='$add3'/></td></tr>");
					   print("<tr><td align='right'>County: </td><td>$countyCombo</td></tr>");
					   print("<tr><td align='right'>Gender: </td><td>$genderRadio</td></tr>"); //<input type='input' size='50' name='txtCounty' />
					   print("<tr><td align='right'>Date of birth (yyyy-mm-dd): </td><td><input type='text' size='50' name='txtDob' id='dobId' value ='$dob' required readonly /></td></tr>");
					   print("<tr><td align='right'>Phone number: </td><td><input type='input' size='50' name='txtPhone' value ='$phoneNum' required/></td></tr>");
					   print("<tr><td align='right'>E-mail: </td><td><input type='email' size='50' name='txtEmail' value ='$email' required/></td></tr>");
					   print("<tr><td align='right'>Password: </td><td><input type='text' size='50' name='txtPassword' value ='$pass' required/></td></tr>");
					   print("<tr><td align='right'></td><td><input type='submit' value='Edit Details' /></td></tr>");
					 
				 print("</form>");
				 
			print("</table>");

?>