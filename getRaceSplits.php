<?php
	$raceId = 0;
	// $raceId = 6;
	if(isset( $_GET['raceId'] ) )
	{
		$raceId = $_GET['raceId']; //get the raceId passed in get parameter
		//print("id =".$raceId);
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
			//original
		 /* $myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position, ";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time)/60,NULL)) AS '$splitNames[$i](mins)',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time)/60 AS 'Finish Time(mins)'
			FROM athlete_race_result, race_split_result
			WHERE  athlete_race_result.race_id = race_split_result.race_id
			AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND race_split_result.race_id = $raceId
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			";*/
			//print("myQuery = ".$myquery);		
			$myquery = "
			select athlete_race_result.series_ath_id AS id,
			athlete_race_result.position, ";						
			for($i=0;$i<$numberSplits;$i++)
			{ 
				$myquery = $myquery."
				GROUP_CONCAT(if(race_split_result.split_order = $splitOrder,TIME_TO_SEC(race_split_result.split_time)/60,NULL)) AS '$splitNames[$i](mins)',
				";
				$splitOrder++;
			}			
			$myquery = $myquery."
			TIME_TO_SEC(athlete_race_result.overall_time)/60 AS 'Finish Time(mins)'
			FROM athlete_race_result, race_split_result
			WHERE  athlete_race_result.race_id = race_split_result.race_id
			AND athlete_race_result.athlete_race_no = race_split_result.athlete_race_no
			AND race_split_result.race_id = $raceId
			GROUP BY athlete_race_result.athlete_race_no
			ORDER BY athlete_race_result.position;
			";
	}
	else //static query for races without splits
	{
		$myquery = "SELECT athlete_race_result.series_ath_id AS id,position, TIME_TO_SEC(overall_time)/60 AS 'Finish Time(Mins)'
					FROM athlete_race_result,series_athlete
					WHERE race_id = $raceId
					ORDER BY position";
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