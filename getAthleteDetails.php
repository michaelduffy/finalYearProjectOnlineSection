<?php

	if(isset( $_GET['addSuccess'] ) )
	{
		$addStatus = $_GET['addSuccess'];
		//print("addStatus = "$addStatus);
		
		if($addStatus === "pass")
		{
			$athId = $_GET['id'];
			$firstName = $_GET['firstName'];
			$lastName = $_GET['lastName'];
			$add1 = $_GET['add1'];
			$add2 = $_GET['add2'];
			$add3 = $_GET['add3'];
			$county= $_GET['county'];
			$gender = $_GET['gender'];
			$dob = $_GET['dob'];
			$phone = $_GET['phone'];
			$email = $_GET['email'];
			$password = $_GET['password'];
			
			$emailSender = "mrEmail@mail.com";
			$comment = "Success!! You have been successfully registered as an athlete in the race series
			\nRace Series Athlete ID : $athId !!(important - required for race registrations)!!
			\nName : $firstName  $lastName
			\nAddress Line 1 : $add1
			\nAddress Line 2 : $add1
			\nAddress Line 3 : $add1
			\nCounty : $county
			\nGender = $gender
			\nDate of Birth= $dob
			\nPhone Number = $phone
			\nEmail = $email
			\nAccount Password = $password";
			
			
			
			
			print("<script language='javascript'>");
			//print('alert("Success!! You have been successfully registered as an athlete in the race series")');
			print('alert("Success!! You have been successfully registered as an athlete in the race series\nAthlete Series  ID - '.$athId.'\nAthlete Name - '.$firstName.' '.$lastName.'\nAddress 1 - '.$add1.'\nAddress 2 - '.$add2.'\nAddress 3 - '.$add3.'\nAthlete County - '.$county.'\nAthlete Gender - '.$gender.'\nDate of Birth - '.$dob.'\nPhone Number - '.$phone.'\nEmail Address - '.$email.'\nPassword - '.$password.'")'); 
			//print('alert("Success athId = '.$athId.'")');		
			print("</script>");
			// mail($admin_email, "$subject", $comment, "From:" . $email);
			mail($email, "subject", $comment, "From:" . $emailSender);
		}
		else if($addStatus === "fail")
		{
			print("<script language='javascript'>");
			print('alert("Error!! We were unable to add you to our race series database at this time due to a technical error!! Please try again")');
			print("</script>");
		}
		else if($addStatus === "invalid")
		{
			print("<script language='javascript'>");
			print('alert("Error!! You appear as already existing in our athlete database!!")');
			print("</script>");
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	print("<head>
			
			<script src='js/jquery-1.12.3.js'> </script>
			<link rel='stylesheet' type='text/css' href='js/jquery.datepick.css''>
			<script type='text/javascript' src='js/jquery.plugin.js'> </script>
			<script type='text/javascript' src='js/jquery.datepick.js'> </script>
			<script src='js/jQueryTest.js'> </script>
		 </head>");	
	print("<body>");
		print("<div id='wrapper'>");
			
			require_once('topNav.html');
			
			print("</br>");
			
			print("<table border=1> <caption><b>Enter Details Below</b></caption>");
						
				 $countyCombo = "<select name='county'> 
							<option value='Donegal'>Donegal</option>
							<option value='Sligo'>Sligo</option>
							<option value='Fermanagh'>Fermanagh</option>
							<option value='Derry'>Derry</option>
							<option value='Tyrone'>Tyrone</option>
							<option value='Lietrim'>Lietrim</option>
							<option value='Mayo'>Mayo</option>
							<option value='Roscommon'>Roscommon</option>
						   </select>";
						   
				$genderRadio = "<input type='radio' name='gender' value='m' />Male <input type='radio' name='gender' value='f'> Female";	
				
				 print("<form method='post' action='addAthlete.php'>");
				 
					   print("<tr><td align='right'>First Name: </td><td><input type='input' size='50' name='txtFirstName' required /></td></tr>");
					    print("<tr><td align='right'>Last Name: </td><td><input type='input' size='50' name='txtLastName' required /></td></tr>");
					   print("<tr><td align='right'>Address line 1: </td><td><input type='input' size='50' name='txtAddress1' id='id1'/></td></tr>");
					   print("<tr><td align='right'>Address line 2: </td><td><input type='input' size='50' name='txtAddress2' /></td></tr>");
					   print("<tr><td align='right'>Address line 3: </td><td><input type='input' size='50' name='txtAddress3' /></td></tr>");
					   print("<tr><td align='right'>County: </td><td>$countyCombo</td></tr>");
					   print("<tr><td align='right'>Gender: </td><td>$genderRadio</td></tr>"); //<input type='input' size='50' name='txtCounty' />
					   print("<tr><td align='right'>Date of birth: </td><td><input type='text' size='50' name='txtDob' id='dobId' required readonly /></td></tr>");
					   print("<tr><td align='right'>Phone number: </td><td><input type='input' size='50' name='txtPhone' required/></td></tr>");
					   print("<tr><td align='right'>E-mail: </td><td><input type='email' size='50' name='txtEmail' required/></td></tr>");
					   print("<tr><td align='right'>Password: </td><td><input type='password' size='50' name='txtPassword' required/></td></tr>");
					   print("<tr><td align='right'></td><td><input type='submit' value='Submit Details' /></td></tr>");
					 
				 print("</form>");
				 
			print("</table>");
		print("</div>");	
	print("</body>");
