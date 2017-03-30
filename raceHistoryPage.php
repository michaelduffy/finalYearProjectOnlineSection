<?php 
require_once('topNav.html'); 

$athId = $_GET['athId'];
$athName = $_GET['athName'];
$currPosition=0;
$currRaceName="";
$currRaceDate="";
$currentRacePoints=0;

  $connection=mysqli_connect("localhost","root",""); 
  mysqli_select_db($connection,"project_database");
 
   $myquery = "
			select athlete_race_result.position, race.race_name, race.race_date,athlete_race_result.ath_race_points
			FROM athlete_race_result,race
			WHERE race.race_id = athlete_race_result.race_id
			AND athlete_race_result.series_ath_id = $athId
			ORDER BY race.race_date DESC
			";
    $result= mysqli_query($connection,$myquery);
    

?>

<body>
<div id ='historyTitleArea'>
<h4 >Race History -<?php print(" ".$athName.""); ?></h4>
</div>
<hr></hr>
<div>
<table border = 1 class='tableClass'>
<tr><th>Position</th><th>Race</th><th>Date</th><th>Points</th></tr>
<?php
	 while($row = mysqli_fetch_array($result))
	{
		$currPosition=$row['position'];
		$currRaceName=$row['race_name'];
		$currRaceDate=$row['race_date'];
		$currentRacePoints=$row['ath_race_points'];
		print("<tr>");
		print("<td>$currPosition</td>");
		print("<td>$currRaceName</td>");
		print("<td>$currRaceDate</td>");
		print("<td>$currentRacePoints</td>");
		print("</tr>");				
	}	
?>
</table>
</div>
</body>
</html>