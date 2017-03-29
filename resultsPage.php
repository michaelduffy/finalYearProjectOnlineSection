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
	
	$minAge=15;
	$maxAge=90;
	$gender = "all";
	
	require_once('topNav.html'); 
	if(isset($_COOKIE["cookieAthId"])) //check if sign in cookieis set
	{
	   $cookieSet=True;
	   $athId=$_COOKIE["cookieAthId"];
	}
	//db connection
	$connection=mysqli_connect("localhost","root",""); 
	mysqli_select_db($connection,"project_database");
	$raceId=0;
	  //////// get first race_id in combobox list for initial page load //////////////////////////////////
	$myquery = "
				select race_id from race 
				";
	$result= mysqli_query($connection,$myquery);
	    
	while($row = mysqli_fetch_array($result))
	{
		$raceId = $row['race_id'];//get first race id
		break; //break out of loop
	}
	  ///////////////////////////////////////////////////////////////////////////////////////
	if(isset( $_POST['raceCombo'] ) )  //for when race is chosen from combobox and form has been self submitted and page reloaded
	{
		$raceId=$_POST['raceCombo']; //get the chosen raceId and assign to $raceId variable
	}
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
	 <script src="parallel.js"></script>
	 <!--  slider links and scripts -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script  src="js/sliderJs.js"></script>
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
		<?php print("<h4>$raceName</h4>")?>
		</hr>
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
						if(isset( $_POST['raceCombo'] ) )  //for when race is chosen from combobox and form has been self submitted and page reloaded
						{							
							$raceId=$_POST['raceCombo']; //get the chosen raceId and assign to $raceId variable
							if ($row['race_id'] == $raceId)
							{
								print("<option selected = 'selected' value =".$row['race_id'].">".$row['race_name']."</option>");//to set seleceted race as default in combo
							}
							else
							{
								print("<option value =".$row['race_id'].">".$row['race_name']."</option>");
							}
						}
						else //initial page load
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
		&nbsp&nbsp&nbsp&nbsp
		<div id="slider-range"></div>			
		  <input id="radioMale" type="radio" name="gender" value="m"> Male
		  <input id="radioFemale" type="radio" name="gender" value="f"> Female
		  <input id="radioAll" type="radio" name="gender" value="all" checked='checked'> All
		  &nbsp&nbsp
		 <button id="btnFilter"> Filter Results</button>
		 
		<!--<div id="filterSelect"></div> -->
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
				FROM athlete_race_result, race_split_result
				WHERE  athlete_race_result.race_id = race_split_result.race_id
				AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
				AND race_split_result.race_id = $raceId
				GROUP BY athlete_race_result.athlete_race_no
				ORDER BY athlete_race_result.position;
				"; 						
			}
			else //non dynamic query
			{
				$myquery = "SELECT position,
						ath_name, 
						athlete_race_no, 
						overall_time AS 'Finish Time', 
						ath_race_points AS 'Race Points', 
						series_ath_id AS 'Series ID'
						FROM athlete_race_result
						WHERE race_id = $raceId
						ORDER BY position";
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
					//print("<tr bgcolor='lightblue'>");
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
		?>
		</table>
	</div>
	<hr/>

	<?php mysqli_close($connection);?>
	
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