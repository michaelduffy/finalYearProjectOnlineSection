<?php
	/*$now = time(); // get todays date
	$your_date = strtotime($currentDob); //get competitor dob as string
	$datediff = $now - $your_date; //get difference in seconds*/
	
	$startAge = 30;
	$startDate = strtotime("- 30 year", time());
	$startDate = strtotime("-".$startAge." year", time());
	$date = date("Y-m-d",$startDate);
	echo "test";
	echo $date;
			
?>