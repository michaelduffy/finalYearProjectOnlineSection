<?php
	$raceId = 0;

	if(isset( $_GET['raceId'] ) )
	{
		$raceId = $_GET['raceId']; //get the raceId passed in get parameter
	}

	$numberSplits=0;
		
	$splitNames = array();
	
	//getting DB connection
	$connection=mysqli_connect("localhost","root",""); 
	mysqli_select_db($connection,"project_database");
	
	//$string = "hello";	   	   
	////////////////////////////// //getting the number of splits for particular race ///////////////////////////////////////////////////////////////////////
	$myquery = "
			select MAX(split_order) from race_split_result where race_id =$raceId
				";
	$result= mysqli_query($connection,$myquery);
	    
	while($row = mysqli_fetch_array($result))
	{
		$numberSplits = $row['MAX(split_order)'];//gets number of splits
	}
	
	////////////////////////////////////////////building sql query dynamically////////////////////////////////////////////////////////
	if($numberSplits>1) //dynamic query
	{
		for($i=0;$i<$numberSplits;$i++)
		{			
			$splitNames[$i]= getSplitName($connection,$raceId,$i+1); //getting the split names
		}

		$splitOrder = 1;	
	
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
    $result= mysqli_query($connection,$myquery);
    
    if ( ! $result ) //nothing returned
    {
        echo mysql_error();
        die;
    }
    
    $data = array();
    
    for ($x = 0; $x < mysqli_num_rows($result); $x++) 
    {
        $data[] = mysqli_fetch_assoc($result);
    }
    
    echo json_encode($data);     
     
    mysqli_close($connection);

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
	
?>