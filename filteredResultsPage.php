<?php 
	$athId=0;
	$numberSplits=0;
	$splitNames = array();
	$pos=0;
	$name="";
	$raceNo=0;
	$splitTime=0;
	$overallTime=0;
	$points=0;
	$seriesId=0;
	$raceName="";
		
	$raceId=$_GET['raceId']; 
	$minAge=$_GET['minAge']; 
	$maxAge=$_GET['maxAge']; 
	$gender=$_GET['gender']; 
	
	$startDate = strtotime("-".$minAge." year", time()); //getting the start date
	$startDate = date("Y-m-d",$startDate);
	$startDate = "'".$startDate."'";
	$endDate = strtotime("-".$maxAge." year", time()); //getting the end date
	$endDate = date("Y-m-d",$endDate);
	$endDate = "'".$endDate."'";
	
	require_once('topNav.html'); 
	if(isset($_COOKIE["cookieAthId"])) // check for sign in cookie
	{
	   $cookieSet=True;
	   $athId=$_COOKIE["cookieAthId"];
	}
		
	  $connection=mysqli_connect("localhost","root",""); 
	  mysqli_select_db($connection,"project_database");
		
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$myquery = "
				select race_name, no_recorded_splits from race where race_id = $raceId
				";
	$result= mysqli_query($connection,$myquery);
	    
	while($row = mysqli_fetch_array($result))
	{
		$raceName = $row['race_name'];//get race name
		break; //break out of loop
	}	
?>
<html>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://d3js.org/d3.v3.min.js"></script>
	 <script src="parallelFiltered.js"></script>
	 <!--  slider links and scripts -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 	
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script script src="js/sliderJs.js"></script>
</head>
<body>
	<div style="display:none">
		 <span id="raceIdSpan"><?php print($raceId) ?></span><!--to store raceId where JQuery can access -->
		<span id="minAgeSpan"><?php print($minAge) ?></span><!--to store minAge where JQuery can access -->
		<span id="maxAgeSpan"><?php print($maxAge) ?></span><!--to store maxAge where JQuery can access -->
		<span id="genderSpan"><?php print($gender) ?></span><!--to store maxAge where JQuery can access -->
		<span id="athIdSpan"><?php print($athId) ?></span><!--to store athleteId where JQuery can access -->
	</div>
	
	<div id="raceTitleArea">
		<?php print("<h4>$raceName (Ages $minAge to $maxAge, gender-$gender) Series athletes only</h4>")?>
		<hr/>
	</div>
	
	<div id="vizSection">
	</div>
	<hr/>
	
	
	
	<div id="selectionArea">
		<form method='post' action='resultsPage.php'>
			Select race: 
			<select id="raceCombo" name="raceCombo">			
				<?php
					$myquery = "
					select race_id, race_name from race 
					";
					$result= mysqli_query($connection,$myquery);
		    
					while($row = mysqli_fetch_array($result))//loading the combobox with race names
					{
							if ($row['race_id'] == $raceId)
							{
								print("<option selected = 'selected' value =".$row['race_id'].">".$row['race_name']."</option>");//to set seleceted race as default in combo
							}
							else
							{
								print("<option value =".$row['race_id'].">".$row['race_name']."</option>");
							}
					}
				?>
			</select>	
			<input type='submit' value='Display Results' />
		</form>
		
			
	</div>	
	
	<div id="filterArea">
		<p>
		  <label id='sliderLabelId' for="amount">Age range:</label>
		  <input type="text" id="amount" readonly style="border:0; color:lightblue; font-weight:bold;">				
		</p>
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
		<div id="slider-range"></div>
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
		  <button id="btnFilter"> Filter Results</button>
		<div id="filterSelect"></div>		
	</div>
					
	<div id="resultSection">
		<hr/>	
		<table id="resultTable" border = "1">
		<?php
		  ////////////////////////////// //getting the number of splits for particular race ////////////
		    $myquery = "
					select MAX(split_order) from race_split_result where race_id =$raceId
					";
		    $result= mysqli_query($connection,$myquery);
		    
		    while($row = mysqli_fetch_array($result))
			{
				$numberSplits = $row['MAX(split_order)'];//gets number of splits
			}
		/////////////////////  store split names in array   ////////////////////////////////////////////
		for($i=0;$i<$numberSplits;$i++)
		{			
			$splitNames[$i]= getSplitName($connection,$raceId,$i+1); //getting the split names
		}
		/////////////////////////////////                Build query dynamically and execute       ///////////////////////////////////////////////////////////////////////////
		if($numberSplits>1)
		{
			$splitOrder = 1;	
			if($gender == 'all')
			{			
				$myquery = "
				select athlete_race_result.position, 
				athlete_race_result.athlete_race_no,
				athlete_race_result.ath_name,";						
				for($i=0;$i<$numberSplits;$i++)
				{ 
					$myquery = $myquery."
					GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,race_split_result.split_time,NULL)) AS '$splitNames[$i]',
					";
					$splitOrder++;
				}			
				$myquery = $myquery."
				athlete_race_result.overall_time AS 'Finish Time',
				athlete_race_result.ath_race_points AS 'Race Points',
				athlete_race_result.series_ath_id AS 'Series ID'
				FROM athlete_race_result, race_split_result,series_athlete
				WHERE  athlete_race_result.race_id = race_split_result.race_id
				AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
				AND athlete_race_result.series_ath_id = series_athlete.athlete_id
				AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
				AND athlete_race_result.race_id = $raceId
				GROUP BY athlete_race_result.athlete_race_no
				ORDER BY athlete_race_result.position;
				";				
			}
			else
			{      				
				$myquery = "
				select athlete_race_result.position, 
				athlete_race_result.athlete_race_no,
				athlete_race_result.ath_name,";						
				for($i=0;$i<$numberSplits;$i++)
				{ 
					$myquery = $myquery."
					GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,race_split_result.split_time,NULL)) AS '$splitNames[$i]',
					";
					$splitOrder++;
				}			
				$myquery = $myquery."
				athlete_race_result.overall_time AS 'Finish Time',
				athlete_race_result.ath_race_points AS 'Race Points',
				athlete_race_result.series_ath_id AS 'Series ID'
				FROM athlete_race_result, race_split_result,series_athlete
				WHERE  athlete_race_result.race_id = race_split_result.race_id
				AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
				AND athlete_race_result.series_ath_id = series_athlete.athlete_id
				AND series_athlete.gender = '".$gender."'
				AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
				AND athlete_race_result.race_id = $raceId
				GROUP BY athlete_race_result.athlete_race_no
				ORDER BY athlete_race_result.position;
				";
			}
			
		}
		else //non dynamic query  
		{
			if($gender != 'all')
			{
				$myquery = "SELECT position,
					ath_name, 
					athlete_race_no, 
					overall_time AS 'Finish Time', 
					ath_race_points AS 'Race Points', 
					series_ath_id AS 'Series ID'
					FROM athlete_race_result,series_athlete
					WHERE athlete_race_result.race_id = $raceId					
					AND athlete_race_result.series_ath_id = series_athlete.athlete_id
					AND series_athlete.gender = '".$gender."'
					AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
					ORDER BY position";
			}
			else
			{
					$myquery = "SELECT position,
					ath_name, 
					athlete_race_no, 
					overall_time AS 'Finish Time', 
					ath_race_points AS 'Race Points', 
					series_ath_id AS 'Series ID'
					FROM athlete_race_result,series_athlete
					WHERE athlete_race_result.race_id = $raceId					
					AND athlete_race_result.series_ath_id = series_athlete.athlete_id
					AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
					ORDER BY position";
			}
		}
		$result= mysqli_query($connection,$myquery);
		/////////////////////////////////////////      build table from query         /////////////////////////////////////////////////////////////////////
		print("<th>position</th><th>Name</th><th>Race No.</th>");
		if($numberSplits>1)
		{
			for($i=0;$i<$numberSplits;$i++)
			{
				print("<th>$splitNames[$i]</th>");
			}
		}
		print("<th>Overall Time</th><th>Race Points</th><th>Series ID</th>");
		while($row = mysqli_fetch_array($result))
		{
			$seriesId = $row['Series ID'];
			if($seriesId != 0)
			{
				print("<tr bgcolor='#e1f298'>");
			}
			else
			{
				print("<tr>");
			}
			$pos = $row['position'];
			$name = $row['ath_name'];
			$raceNo = $row['athlete_race_no'];
			print("<td>$pos</td><td>$name</td><td>$raceNo</td>");
			if($numberSplits>1)
			{
				for($i=0;$i<$numberSplits;$i++)
				{
					$splitTime = $row[$splitNames[$i]];
					print("<td>$splitTime</td>");
				}
			}
			$overallTime = $row['Finish Time'];
			$points= $row['Race Points'];
			
			print("<td>$overallTime</td><td>$points</td><td>$seriesId</td>");
			print("</tr>");
		}
		mysqli_close($connection);		
		?>
		</table>
	</div>
	<hr/>			
</body>
</html>
<?php
	function getSplitName($connIn,$raceIdIn,$splitOrderIn)//function to get split name
    {
	$myquery = "
			select DISTINCT(split_name) FROM race_split_result WHERE race_id =$raceIdIn AND split_order = $splitOrderIn 
			";
		    $result= mysqli_query($connIn,$myquery);
		    
		    while($row = mysqli_fetch_array($result))
			{
				$splitName = $row['split_name'];//gets name of split
			}
			
			return $splitName;
    }
?>