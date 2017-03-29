<?php 
	require_once('topNav.html'); 
	$minAge=15;
	$maxAge=90;
	$gender = "all";
	
	if(isset($_GET['minAge'])) //if filter option has been selected get this info
	{
		$minAge=$_GET['minAge']; 
		$maxAge=$_GET['maxAge']; 
		$gender=$_GET['gender']; 
	}
?>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	 <!--  slider links and scripts -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
	  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	  <script  src="js/sliderJs.js"></script>
</head>


<body>
	<div style="display:none"> <!-- area to store values accessable by JQuery that will not be displayed on screen -->
	    <span id="minAgeSpan"><?php print($minAge) ?></span><!--to store minAge where JQuery can access -->
	    <span id="maxAgeSpan"><?php print($maxAge) ?></span><!--to store maxAge where JQuery can access -->
	    <span id="genderSpan"><?php print($gender) ?></span><!--to store maxAge where JQuery can access -->	     
	</div>
	<h4 class="seriesStandingsTitleClass">Series Standings - <?php if(isset($_GET['minAge'])) 
												{
													if($gender === 'f')
														print("Female ".$minAge." - ".$maxAge);
													else if($gender === 'm')
														print("Male ".$minAge." - ".$maxAge);
													else
														print("All gender ".$minAge." - ".$maxAge);
												}
												else
												{
													print("All Competitors");
												} ?></h4>
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
	
	//db connection
	$connection=mysqli_connect("localhost","root",""); 
	mysqli_select_db($connection,"project_database");
	
	if(isset($_GET['minAge'])) //if filter button has been selected
	{
		$startDate = strtotime("-".$minAge." year", time()); //getting the start date from minAge number
		$startDate = date("Y-m-d",$startDate);
		$startDate = "'".$startDate."'";
		$endDate = strtotime("-".$maxAge." year", time()); //getting the end date rom maxAge number
		$endDate = date("Y-m-d",$endDate);
		$endDate = "'".$endDate."'";
				
		if($gender != 'all') //if male or female has been selected use this query
		{
			$myquery1 = "
				SELECT athlete_race_result.ath_name, series_athlete.gender, athlete_race_result.series_ath_id,series_athlete.date_of_birth 
				FROM athlete_race_result, series_athlete 
				WHERE athlete_race_result.series_ath_id = series_athlete.athlete_id 
				AND series_athlete.gender = '".$gender."'
				AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate	
				GROUP BY athlete_race_result.series_ath_id
				ORDER BY athlete_race_result.series_ath_id
				";
		}
		else //otherwise use this query
		{
			$myquery1 = "
				SELECT athlete_race_result.ath_name, series_athlete.gender, athlete_race_result.series_ath_id,series_athlete.date_of_birth 
				FROM athlete_race_result, series_athlete 
				WHERE athlete_race_result.series_ath_id = series_athlete.athlete_id 				
				AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate	
				GROUP BY athlete_race_result.series_ath_id
				ORDER BY athlete_race_result.series_ath_id
				";
		}
	}
	else //first loading of page construct this query
	{
		 $myquery1 = "
				SELECT athlete_race_result.ath_name, series_athlete.gender, athlete_race_result.series_ath_id,series_athlete.date_of_birth 
				FROM athlete_race_result, series_athlete 
				WHERE athlete_race_result.series_ath_id = series_athlete.athlete_id 
				GROUP BY athlete_race_result.series_ath_id
				ORDER BY athlete_race_result.series_ath_id
				";
	}
	$result1= mysqli_query($connection,$myquery1);
	$i = 0;
	while($row = mysqli_fetch_array($result1)) //traverse down through result set
	{
			$namesArray[$i] = $row['ath_name'];
			$seriesIdArray[$i] = $row['series_ath_id'];
			$currentAthId = $row['series_ath_id'];
			$currentDob = $row['date_of_birth'];
			$gendersArray[$i] = $row['gender'];

			//////////////////// calculating competitor age ///////////////////////////////
			$now = time(); // get todays date
			$your_date = strtotime($currentDob); //get competitor dob as string
			$datediff = $now - $your_date; //get difference in seconds

			$numDays = floor($datediff / (60 * 60 * 24)); //convert to days
			$numYears = floor($numDays/365); // convert days to years
			$agesArray[$i] = $numYears; //assign to ages array
									
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
	}//end of while loop
		
		///////////////////////////// sort parallel arrays based on points low to high/////////////////////////////////////////////////
		$unSorted = true;
		$temp;
		
		while($unSorted)
		{
			$unSorted = false;
			for($i=0;$i<count($pointsArray)-1;$i++) // sorting now with bubble sort
			{
				if($pointsArray[$i]<$pointsArray[$i+1]) //if swap is required based on points array values
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
					
					$unSorted = true; //swaps required so arrays are still unsorted
				}
			}
		}
		//construct table using array values /////////////////		
		print("<table class='tableClass' border =1>");
		print("<tr><th>Name</th><th>Gender</th><th>Age</th><th>Series ID</th><th>Total Points</th><th>Races Completed</th><th>Race History</th></tr>");
		for($i=0;$i<count($namesArray);$i++) //testing gotten values // need sorting now
		{
			if($gendersArray[$i] == 'm')
			{
				print("<tr>");
			}
			else //if female competitor highlight using differnt background colour to male
			{
				print("<tr bgcolor='#e1f298'>");
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
	</div>	
	<hr/>
	<div id="filterArea">	<!-- div area to house filtering objects -->	
			<p>
			  <label id='sliderLabelId' for="amount">Age range:</label>
			  <input type="text" id="amount" readonly style="border:0; color:lightblue; font-weight:bold;">
			
			</p>
			&nbsp&nbsp&nbsp&nbsp
			<div id="slider-range"></div> <!-- div area to house dual slider -->	
			<?php
				if($gender == 'm')
				{				
					print('<input type="radio" name="gender" value="m" checked="checked"/> Male');
					print('<input type="radio" name="gender" value="f"/> Female');
					print('<input type="radio" name="gender" value="all"/> All');
				}
				else if($gender == 'f')
				{
					print('<input type="radio" name="gender" value="m" /> Male');
					print('<input type="radio" name="gender" value="f" checked="checked"/> Female');
					print('<input type="radio" name="gender" value="all"/> All');
				}
				else
				{
					print('<input type="radio" name="gender" value="m" /> Male');
					print('<input type="radio" name="gender" value="f" /> Female');
					print('<input type="radio" name="gender" value="all" checked="checked"/> All');
				}
			?>&nbsp&nbsp
			 <button id="btnFilterStandings"> Filter Results</button>
			 
			 <!--	<div id="filterSelect"></div>  -->		
	</div>
	<hr/>
</body>
</html>