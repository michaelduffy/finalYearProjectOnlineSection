<?php
	$raceId = 0;
	$gender="";
	// $raceId = 6;
	if(isset( $_GET['raceId'] ) )
	{
		$raceId = $_GET['raceId']; //get the raceId passed in get parameter
		$minAge = $_GET['minAge']; //get the minAge passed in get parameter
		$maxAge = $_GET['maxAge']; //get the maxAge passed in get parameter
		$gender = $_GET['gender']; //get the maxAge passed in get parameter
		//$gender = "'".$gender."'";
		//$gender = $_GET['gender']; //get the raceId passed in get parameter
		//print("id =".$raceId);
		
		//$startAge = 30;
		//$startDate = strtotime("- 30 year", time());
		$startDate = strtotime("-".$minAge." year", time()); //getting the start date
		$startDate = date("Y-m-d",$startDate);
		$startDate = "'".$startDate."'";
		$endDate = strtotime("-".$maxAge." year", time()); //getting the end date
		$endDate = date("Y-m-d",$endDate);
		$endDate = "'".$endDate."'";
		//echo "test";
		//echo $startDate."  //// ".$endDate;
	}

	$numberSplits=0;
		
	$splitNames = array();
	
	   $connection=mysqli_connect("localhost","root",""); 
	    mysqli_select_db($connection,"project_database");
	    //$result = mysqli_query($connection,"select athlete_id, ath_first_name, ath_last_name, phone_num from series_athlete");
	    
	    $string = "hello";
	    //raceId passed in post variable
	   
	   
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   ////////////////////////////// //getting the number of splits for particular race ///////////////////////////////////////////////////////////////////////
	    $myquery = "
				select MAX(split_order) from race_split_result where race_id =$raceId
				";
	    $result= mysqli_query($connection,$myquery);
	    
	    while($row = mysqli_fetch_array($result))
		{
			$numberSplits = $row['MAX(split_order)'];//gets number of splits
		}
	//print("number splits = $numberSplits");
	
	////////////////////////////////////////////building sql query dynamically////////////////////////////////////////////////////////
	if($numberSplits>1) //dynamic query
	{
		for($i=0;$i<$numberSplits;$i++)
		{			
			$splitNames[$i]= getSplitName($connection,$raceId,$i+1); //getting the split names
		}
		//print($splitNames[0]." ".$splitNames[1]." ".$splitNames[2]." ".$splitNames[3]);	
		//$string = "";
		
		/*$string = "mi";
		print("string = ".$string);
		for($i=0;$i<2;$i++)
		{ $string = $string."d";}
		print("string = ".$string);
		$string = $string."le";
		print("string = ".$string);*/
		$splitOrder = 1;	
		if($gender == "all") //if all has been selected for gender
		{
		//original
			/*$myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position, ";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time),NULL)) AS '$splitNames[$i]',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time) AS 'Finish Time'
			FROM athlete_race_result, race_split_result,series_athlete
			WHERE athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND athlete_race_result.series_ath_id = series_athlete.athlete_id
			AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
			AND athlete_race_result.race_id = $raceId
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			";*/
			
			$myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position, ";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time)/60,NULL)) AS '$splitNames[$i](Mins)',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time)/60 AS 'Finish Time(mins)'
			FROM athlete_race_result, race_split_result,series_athlete
			WHERE  athlete_race_result.race_id = race_split_result.race_id
			AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND athlete_race_result.series_ath_id = series_athlete.athlete_id
			AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
			AND athlete_race_result.race_id = $raceId
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			";
			
			
			//print("myQuery = ".$myquery);		
		}
		else //if selected gender is male or female
		{
			//original
			/* $myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position,";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time),NULL)/60) AS '$splitNames[$i](mins)',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time)/60 AS 'Finish Time(mins)'
			FROM athlete_race_result, race_split_result,series_athlete
			WHERE athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND athlete_race_result.series_ath_id = series_athlete.athlete_id
			AND series_athlete.gender = '".$gender."'
			AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate			
			AND athlete_race_result.race_id = $raceId			
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			"; */
			
			$myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position,";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time),NULL)/60) AS '$splitNames[$i](mins)',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time)/60 AS 'Finish Time(mins)'
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
	else //static query for races without splits
	{
		if($gender == 'all')
		{
			$myquery = "SELECT athlete_race_result.series_ath_id AS id,
					position, 			
					TIME_TO_SEC(overall_time)/60 AS 'Finish Time(Mins)'
					FROM athlete_race_result,series_athlete
					WHERE race_id = $raceId
					AND athlete_race_result.series_ath_id = series_athlete.athlete_id
					AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
					ORDER BY position";
		}
		else
		{
			$myquery = "SELECT athlete_race_result.series_ath_id AS id,
			                position,
					TIME_TO_SEC(overall_time)/60 AS 'Finish Time(Mins)'
					FROM athlete_race_result,series_athlete
					WHERE race_id = $raceId
					AND athlete_race_result.series_ath_id = series_athlete.athlete_id
					AND series_athlete.gender = '".$gender."'
					AND series_athlete.date_of_birth BETWEEN $endDate AND $startDate
					ORDER BY position";
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////execute query and converting data to JSON//////////////////////////////////////////////////////////////////////////
  /*  $myquery = "
			select time_to_sec(ath_start_time)AS 'start in secs',time_to_sec(overall_time)AS $string from athlete_race_result where race_id =6 
			";*/
    $result= mysqli_query($connection,$myquery);
    
    if ( ! $result ) {
        echo mysql_error();
        die;
    }
    
    $data = array();
    
    for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }
    
    echo json_encode($data);     
     
    mysqli_close($connection);
  //  header("Location: resultsPage.php");
    
    //////////////////////////////////// functions /////////////////////////////////////////////////////////////////////////////////////
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
    //////////////////////////  end of functions ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////get  results where number of splits is 2 (non dynamic query building)////////////////////////////////////////////////////////
	/*if($numberSplits == 2)
	{
		$split1 = getSplitName($connection,6,1); //getting the split names
		$split2 = getSplitName($connection,6,2);		
		
		  $myquery = "
			select athlete_race_result.position, 
			GROUP_CONCAT(if(race_split_result.split_order = 1,TIME_TO_SEC(race_split_result.split_time),NULL)) AS '$split1',
			GROUP_CONCAT(if(race_split_result.split_order = 2,TIME_TO_SEC(race_split_result.split_time),NULL)) AS '$split2',					
			TIME_TO_SEC(athlete_race_result.overall_time) AS 'Finsh Time'
			FROM athlete_race_result, race_split_result
			WHERE athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND athlete_race_result.race_id = $raceId
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			";
			//print($split1." ".$split2." ".$split3);		
	} */
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
?>