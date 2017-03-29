$( function() 
{
	    var $value1=0;
	    var $value2=0;
	    var $gender="";
	    var $minAge=0;
	    var $maxAge=0;
	    var $raceId=0;
	    //var $genderSpan;
	
	$("#btnFilter").click(goToFilterPage);//when filter results button is clicked
	$("#btnFilterStandings").click(goToStandingsWithFilters);//when filter results button is clicked
	$("#filterArea input[type='radio']").on("change",getGenderValue); //when radio button filter is changed //#filterForm
	
	
	$minAge = $("#minAgeSpan").text(); //getting values to set selected slider values when page is loaded
	$maxAge = $("#maxAgeSpan").text();
		
	function goToStandingsWithFilters() //action on filter results button click
	{
		$minAge = $("#minAgeSpan").text();
		$minAge=parseInt($minAge);
		console.log("minAge = "+$minAge);
		$maxAge = $("#maxAgeSpan").text();
		$gender = $("#genderSpan").text();
		//$raceId = $("#raceIdSpan").text();
		//console.log("id = "+$raceId+", gender = "+$gender+", min = "+$minAge+", max = "+$maxAge+"");
		window.location.href = "seriesStandingsPage.php?minAge="+$minAge+"&maxAge="+$maxAge+"&gender="+$gender+"";
	}
	
	function goToFilterPage() //action on filter results button click
	{
		$minAge = $("#minAgeSpan").text();
		$minAge=parseInt($minAge);
		console.log("minAge = "+$minAge);
		$maxAge = $("#maxAgeSpan").text();
		$gender = $("#genderSpan").text();
		$raceId = $("#raceIdSpan").text();
		//console.log("id = "+$raceId+", gender = "+$gender+", min = "+$minAge+", max = "+$maxAge+"");
		window.location.href = "filteredResultsPage.php?raceId="+$raceId+"&minAge="+$minAge+"&maxAge="+$maxAge+"&gender="+$gender+"";
	}
	
	
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
				//var value = $( "#minAgeSpan" ).slider( "values", 0 );
				//$("minAgeSpan").text();
				//console.log("value 1 = "+value);
			}
		});
		$( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
		" - " + $( "#slider-range" ).slider( "values", 1 ) );
		
		// assign values to hidden html spans on slider movement
		$( "#slider-range" ).slider({
		  change: function( event, ui ) 
			{   
				$value1 = $( "#slider-range" ).slider( "values", 0 );
				$value2 = $( "#slider-range" ).slider( "values", 1 );
				//$("minAgeSpan").text();
				//console.log("value 1 = "+$value1); 
				//console.log("value 2 = "+$value2); 
				$("#minAgeSpan").text($value1);
				$("#maxAgeSpan").text($value2);
				//console.log("span 1 = "+$("#minAgeSpan").text()); 
				//console.log("span 2 = "+$("#maxAgeSpan").text()); 
			}
		});
		
		//console.log("test = "+$(":checked").val());
		
		
		
		
		
		
		function getGenderValue() //action on radio button filter change
		{
			$gender = $("#filterArea input[type='radio']:checked").val();
			$("#genderSpan").text($gender);
			console.log("gender = "+$gender)
			//console.log("spanValue6 = "+$("#genderSpan").text());
			
			//return $gender;
		}
		    
    
  } );