<?php require_once('topNav.html'); ?>
<html>
<body>
	<div id='homePageContent'>
	<h3>About the Results Page Visualization</h3>
	<p>
	The parallel co-ordinates visualization on the results page provides a unique way to view and analyze race results.
		<ul>
			<li>Each competitor is represented as a single line moving from left to right through the vertical axes.</li>
			<li>The left-most vertical axis represents competitor finishing positions.</li>
			<li>The right-most vertical axis represents competitor finishing times in minutes.</li>
			<li>Vertical axes between the first and last represent competitor split times in minutes.</li>
			<li> The visualization lines can be filtered by simply clicking and dragging on any of the vertical axis to view lines only within the selected values.</li>
			<li> The filter is removed by simply clicking on the fitered vertical axis anywhere outside of the filter box.</li>
			<li> The visualization lines can be further filtered by simply gender and age values on the controls below the visualization and pressing the "Filter results" button.</li>
			<li> When logged in your individual line within the visualization will be highlited in red with all others in light blue.</li>
		</ul>	
	<p>
	<hr/>
	<h3>About the Series Standings Page</h3>
	<p>
	The series standings are calculated based on a competitiors total score from their best n (in this instance 2) number of races.
		<ul>
			<li>Standings are initially displayed for all competitors i.e. male and female and all ages.</li>
			<li>Female competitiors are highlighted in light green.</li>
			<li>Standings can be filtered by age and gender, simply select the controls below the standings list and pressing the 'Filter Results' button.</li>
			<li>A competitiors complete race history can be viewed by selecting their respective 'View History' button.</li>			
		</ul>	
	<p>
	<hr/>
	<h3>Registering for the series</h3>
	<p>
	To be eligible to race in the series you must register by filling and submitting the registration form on the 'Register' page.
		<ul>
			<li>Upon successfull registration your details together with your unique competitor series ID number will be displayed on screen.</li>
			<li>These details and unique competitor series ID will also be emailed to you.</li>
			<li>Take note of your competitor ID as this is need when registering in any of the series races.</li>
			<li>You will only recieve points for races that take place on dates AFTER the date you register.</li>			
		</ul>	
	<p>
	<hr/>
	<h3>Race points calculation</h3>
	<p>
	Race points are calculated on times as opposed to position, this means every second counts when you race.
		<ul>
			<li>Basis for calculation is the time of the competitor at the 30th percentile position of total race finishers.(30th Time)</li>
			<li>The competitor at the 30th percentile position of total race finishers will receive 100 points exactly.</li>
			<li>Race points for all other competitors are calculated using the formula below.</li>
			<li>(30th Time X 100)/competitor finish time.</li>			
		</ul>	
	<p>
	</div>
</body>
</html>