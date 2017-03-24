<?php require_once('topNav.html'); ?>

<body>
<h4 class="seriesStandingsTitleClass">Series Standings - All</h3>
	<?php
	$namesArray=array();
	$gendersArray=array();
	$seriesIdArray=array();
	$pointsArray=array();
	$agesArray=array();
	$noCompetitorRacesArray=array();
	$noCompletedRaces=0;
	$totalAthPoints=0;
	$numberScoringRaces=2;
	$currentAthId=0;
	$currentDob;
	//$yearStart="2017-01-01";
	
	  $connection=mysqli_connect("localhost","root",""); 
	  mysqli_select_db($connection,"project_database");
	 // $raceId=0;
	
	   $myquery1 = "
				SELECT athlete_race_result.ath_name, series_athlete.gender, athlete_race_result.series_ath_id,series_athlete.date_of_birth 
				FROM athlete_race_result, series_athlete 
				WHERE athlete_race_result.series_ath_id = series_athlete.athlete_id 
				GROUP BY athlete_race_result.series_ath_id
				ORDER BY athlete_race_result.series_ath_id
				";
					
	    $result1= mysqli_query($connection,$myquery1);
	    $i = 0;
	    while($row = mysqli_fetch_array($result1))
		{
			
			//$today = date("y-m-d");
						
			//print("$today");
			$namesArray[$i] = $row['ath_name'];
			$seriesIdArray[$i] = $row['series_ath_id'];
			$currentAthId = $row['series_ath_id'];
			$currentDob = $row['date_of_birth'];
			$gendersArray[$i] = $row['gender'];
			//$currentDob = date("Y-m-d");
			//////////////////// calculating competitor age ///////////////////////////////
			$now = time(); // get todays date
			$your_date = strtotime($currentDob); //get competitor dob as string
			$datediff = $now - $your_date; //get difference in seconds

			//echo floor($datediff / (60 * 60 * 24));
			$numDays = floor($datediff / (60 * 60 * 24)); //convert to days
			$numYears = floor($numDays/365); // convert days to years
			$agesArray[$i] = $numYears; //assign to ages array
			//echo ",,,,,";
			//echo $numYears;
			
			//$dateDiff = $today-$currentDob;
		//	print("today = ".$now.",,,,currentDOB = ".$your_date.",,,,,dateDiff = ".$datediff."");
			//////////////////////////////////////////////////////////////////////////////////////////////////
		//	print("<br>");
			
			
			/////////////////////////////////////get competetitors total points for best n($numberScoringRaces) races///////////////////////////////////////////////////////////////////////////////////
			 $myquery2 = "
				SELECT SUM(subt.ath_race_points) AS 'total_points'
				FROM (select athlete_race_result.ath_race_points FROM athlete_race_result WHERE athlete_race_result.series_ath_id = $currentAthId
				ORDER BY athlete_race_result.ath_race_points DESC LIMIT $numberScoringRaces) as subt
				";
				
			$result2= mysqli_query($connection,$myquery2);
			while($row = mysqli_fetch_array($result2))
			{
				$totalAthPoints = $row['total_points'];
			}
			$pointsArray[$i] = $totalAthPoints;
			////////////////////////////////////////////////get number of races completed by competitor///////////////////////////////////////////////////////////
			 $myquery3 = "
				SELECT count(ath_race_points) AS 'number_completed_races'
				FROM athlete_race_result
				WHERE series_ath_id = $currentAthId
				";
				
			$result3= mysqli_query($connection,$myquery3);
			while($row = mysqli_fetch_array($result3))
			{
				$noCompletedRaces = $row['number_completed_races'];
			}
			$noCompletedRacesArray[$i] = $noCompletedRaces;
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$i++;
		}
		
		/*for($i=0;$i<count($namesArray);$i++) //testing gotten values // need sorting now
		{
			print("$i = ".$i.",,,");
			print("name =  ".$namesArray[$i].",,,,id =  ".$seriesIdArray[$i].",,,,,points =  ".$pointsArray[$i].",,,,,no comp races =  ".$noCompletedRacesArray[$i].",,,,,comp age =  ".$agesArray[$i]."");
			print("<br>");
		} */
		///////////////////////////// sort parallel arrays based on points low to high/////////////////////////////////////////////////
		$unSorted = true;
		/*$nameTemp="";
		$ageTemp="";
		$idTemp=0;
		$pointsTemp=0;
		$racesCompletedTemp=0;*/
		$temp;
		
		while($unSorted)
		{
			//print("entered1 while,,,,,,,,");
			$unSorted = false;
			for($i=0;$i<count($pointsArray)-1;$i++) // sorting now with bubble sort
			{
				if($pointsArray[$i]<$pointsArray[$i+1])
				{
					///// swap in pointsArray/////
					$temp = $pointsArray[$i];
					$pointsArray[$i] = $pointsArray[$i+1];
					$pointsArray[$i+1] = $temp;
					
					///// swap in namesArray/////
					$temp = $namesArray[$i];
					$namesArray[$i] = $namesArray[$i+1];
					$namesArray[$i+1] = $temp;
					
					///// swap in agesArray/////
					$temp = $agesArray[$i];
					$agesArray[$i] = $agesArray[$i+1];
					$agesArray[$i+1] = $temp;
					
					///// swap in seriesIdArray/////
					$temp = $seriesIdArray[$i];
					$seriesIdArray[$i] = $seriesIdArray[$i+1];
					$seriesIdArray[$i+1] = $temp;
					
					///// swap in noRacesCompletedIdArray/////
					$temp = $noCompletedRacesArray[$i];
					$noCompletedRacesArray[$i] = $noCompletedRacesArray[$i+1];
					$noCompletedRacesArray[$i+1] = $temp;
					
					///// swap in gendersArray/////
					$temp = $gendersArray[$i];
					$gendersArray[$i] = $gendersArray[$i+1];
					$gendersArray[$i+1] = $temp;
					
					
					$unSorted = true;
				}
			}
		}
		
		/*for($i=0;$i<count($namesArray);$i++) //testing gotten values // need sorting now
		{
			
			print("".$pointsArray[$i].",,,,");
			print("<br>");
			print("".$namsArray[$i].",,,,");
			print("<br>");
			print("".$pointsArray[$i].",,,,");
			print("<br>");
			
		}*/
		
		print("<table class='tableClass' border =1>");
		print("<tr><th>Name</th><th>Gender</th><th>Age</th><th>Series ID</th><th>Total Points</th><th>Races Completed</th><th>Race History</th></tr>");
		for($i=0;$i<count($namesArray);$i++) //testing gotten values // need sorting now
		{
			if($gendersArray[$i] == 'm')
			{
				print("<tr bgcolor='#CBDCBF'>");
			}
			else
			{
				print("<tr bgcolor='#BDC8B5'>");
			}	
			print("<td>$namesArray[$i]</td>");
			print("<td>$gendersArray[$i]</td>");
			print("<td>$agesArray[$i]</td>");
			print("<td>$seriesIdArray[$i]</td>");
			print("<td>$pointsArray[$i]</td>");
			print("<td>$noCompletedRacesArray[$i]</td>");		
			print("<td align='center'><a href='raceHistoryPage.php?athId=$seriesIdArray[$i]&athName=$namesArray[$i]' class='historyBtnClass'>View History</a></td>");			
			print("</tr>");
			
		} 
		print("</table>");

	?>
</body>
</html>
<?php

?>