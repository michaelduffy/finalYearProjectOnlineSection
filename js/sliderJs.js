$( function() 
{
	var $value1=0;
	var $value2=0;
	var $gender="";
	var $minAge=0;
	var $maxAge=0;
	var $raceId=0;
	
	$("#btnFilter").click(goToFilterPage);//when filter results button is clicked
	$("#btnFilterStandings").click(goToStandingsWithFilters);//when filter results button is clicked
	$("#filterArea input[type='radio']").on("change",getGenderValue); //when radio button filter is changed //#filterForm
		
	$minAge = $("#minAgeSpan").text(); //getting values to set selected slider values when page is loaded
	$maxAge = $("#maxAgeSpan").text();
		
	function goToStandingsWithFilters() //action on filter standings button click
	{
		$minAge = $("#minAgeSpan").text();
		$maxAge = $("#maxAgeSpan").text();
		$gender = $("#genderSpan").text();
		//go to page
		window.location.href = "seriesStandingsPage.php?minAge="+$minAge+"&maxAge="+$maxAge+"&gender="+$gender+"";
	}
	
	function goToFilterPage() //action on filter race results button click
	{
		$minAge = $("#minAgeSpan").text();		
		$maxAge = $("#maxAgeSpan").text();
		$gender = $("#genderSpan").text();
		$raceId = $("#raceIdSpan").text();
		//go to page
		window.location.href = "filteredResultsPage.php?raceId="+$raceId+"&minAge="+$minAge+"&maxAge="+$maxAge+"&gender="+$gender+"";
	}
	
	//creating and adding dual slider
	$( "#slider-range" ).slider(
		{
		      range: true,
		      min: 10,
		      max: 100,
		      values: [ $minAge, $maxAge ],
		      slide: function( event, ui ) 
			{
				$( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
				console.log("slidertestIng1");
			}
		});
		$( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
		" - " + $( "#slider-range" ).slider( "values", 1 ) );
		
		// assign values to hidden html spans on slider movement
		$( "#slider-range" ).slider({
		  change: function( event, ui ) 
			{   
				$value1 = $( "#slider-range" ).slider( "values", 0 );//get  slider min value
				$value2 = $( "#slider-range" ).slider( "values", 1 );//get slider max value
				$("#minAgeSpan").text($value1); //store minAge value in #minAgeSpan of calling page
				$("#maxAgeSpan").text($value2); //store minAge value in #minAgeSpan of calling page
			}
		});
	
		function getGenderValue() //action on radio button filter change
		{
			$gender = $("#filterArea input[type='radio']:checked").val(); //get selected radio button value
			$("#genderSpan").text($gender); //store gender value in #genderSpan of calling page
		}
		    
    
  } );