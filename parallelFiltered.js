window.onload = function()
	{
	  initFunction();
	}

function initFunction()
{
	
	
	var $raceId = $("#raceIdSpan").text(); //get stored raceId from filteredResultsPage.php using JQuery
	var $minAge = $("#minAgeSpan").text(); //get stored raceId from filteredResultsPage.php using JQuery
	var $maxAge = $("#maxAgeSpan").text(); //get stored raceId from filteredResultsPage.php using JQuery
	var $gender = $("#genderSpan").text(); //get stored raceId from filteredResultsPage.php using JQuery
	var $athId = $("#athIdSpan").text(); //get stored athId from filteredResultsPage.php using JQuery	
	
	/* parallel co-ordinates visualization adapted from example by Mike Bostock
	available at (https://bl.ocks.org/mbostock/1341021)!!!!!!!!!!!!!*/
	
	//getting the document dimensions etc. to determine visualization dimensions	
	var margin = {top: 30, right: 10, bottom: 10, left: 10},	  	
	width = document.body.clientWidth - margin.left - margin.right,
	height = d3.max([document.body.clientHeight-440, 240]) - margin.top - margin.bottom;
	
	//selecting the x and y scale type
	var x = d3.scale.ordinal().rangePoints([0, width], 1),
	    y = {};

	var line = d3.svg.line(),
	    axis = d3.svg.axis().orient("left"),
	    background,
	    foreground1,foreground2;

	var svg = d3.select("#vizSection").append("svg")
	    .attr("width", width + margin.left + margin.right)
	    .attr("height", height + margin.top + margin.bottom)
	  .append("g")
	    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");	    
		
	d3.json("getFilteredRaceSplits.php?raceId="+$raceId+"&minAge="+$minAge+"&maxAge="+$maxAge+"&gender="+$gender+"", function(error, splits) 
	{
		// Extract the list of dimensions and create a scale for each.
		  x.domain(dimensions = d3.keys(splits[0]).filter(function(d) {
		    return d != "id" && (y[d] = d3.scale.linear()
			.domain(d3.extent(splits, function(p) { return +p[d]; }))
			.range([height, 0]));
			
		  }));

		  
		  if($athId != 0) //if user is logged in
		{		  
		     foreground2 = svg.append("g")
		       .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")
		     .filter(function(d) { return d.id != $athId })//lines NOT associated with logged in athlete
		      .attr("d", path); 
		     
		      foreground1 = svg.append("g")
		      .attr("class", "foreground")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")
		     .filter(function(d) { return d.id == $athId })//lines associated with logged in athlete		
		      .attr("d", path);
		}    
		else //user not logged in
		{
			 foreground2 = svg.append("g")
		      .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")		    
		      .attr("d", path); 
		}

		  // Add a group element for each dimension.
		  var g = svg.selectAll(".dimension")
		      .data(dimensions)
		    .enter().append("g")
		      .attr("class", "dimension")
		      .attr("transform", function(d) { return "translate(" + x(d) + ")"; });

		  // Add an axis and title.
		  g.append("g")
		      .attr("class", "axis")
		      .each(function(d) { d3.select(this).call(axis.scale(y[d])); })
		    .append("text")
		      .style("text-anchor", "middle")
		      .attr("y", -9)
		      .text(function(d) { return d; });

		  // Add and store a brush for each axis.
		  g.append("g")
		      .attr("class", "brush")
		      .each(function(d) { d3.select(this).call(y[d].brush = d3.svg.brush().y(y[d]).on("brush", brush)); })
		    .selectAll("rect")
		      .attr("x", -8)
		      .attr("width", 16);
	});
		
	///////////////////////////////////////////// functions///////////////////////////////////////////////////////////////////////////
	// Returns the path for a given data point.
	function path(d) {
	  return line(dimensions.map(function(p) { return [x(p), y[p](d[p])]; }));
	}

	// Handles a brush event, toggling the display of foreground lines.
	function brush() {
	  var actives = dimensions.filter(function(p) { return !y[p].brush.empty(); }),
	      extents = actives.map(function(p) { return y[p].brush.extent(); });
	  foreground2.style("display", function(d) {
	    return actives.every(function(p, i) {
	      return extents[i][0] <= d[p] && d[p] <= extents[i][1];
	    }) ? null : "none";
	  });
	}
   
	
  }	
	   