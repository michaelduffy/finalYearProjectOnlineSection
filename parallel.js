window.onload = function()
	{
	  initFunction();
	}

function initFunction()
{
	
	//var raceId= document.getElementById("weekSpan").innerHTML;
	var $raceId = $("#raceIdSpan").text(); //get stored raceId from raceResultsPage.php using JQuery
	var $athId = $("#athIdSpan").text(); //get stored athId from raceResultsPage.php using JQuery
	console.log("testing12223 "+$raceId);
	
	var margin = {top: 30, right: 10, bottom: 10, left: 10},	  	
	width = document.body.clientWidth - margin.left - margin.right,
	height = d3.max([document.body.clientHeight-440, 240]) - margin.top - margin.bottom;
	
	 // width = 1260 - margin.left - margin.right,
	  //  height = 400 - margin.top - margin.bottom;
	
	// Parse the date / time
	//var parseDate = d3.time.format("%h-%m-%s").parse;

	var x = d3.scale.ordinal().rangePoints([0, width], 1),
	//var x = d3.time.scale().range([0, width]);
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
		    
	d3.json("getRaceSplits.php?raceId="+$raceId+"", function(error, splits) 
	{
				
		/*	console.log("key[0] = "+d3.keys(splits[0]));
		  // Extract the list of dimensions and create a scale for each.
		 x.domain(dimensions = d3.keys(splits[0]).filter(function(d) {
			// console.log("d = "+d);
			 console.log("splitsLength11 = "+splits.length);
			console.log("d.over = "+d);
			
				 
		    return d = (y[d] = d3.time.scale()
			.domain(d3.extent(splits, function(p) {  console.log("pd = "+p[d]);return parseDate(+p[d]); }))
			.range([height, 0])); 
			
			console.log("splitsLength = "+splits.length);
			
			splits.forEach(function(d) {
			d.overall_time = parseDate(d.date);
				 console.log("d.over = "+d);
			});*/
			
			// Extract the list of dimensions and create a scale for each.
		  x.domain(dimensions = d3.keys(splits[0]).filter(function(d) {
		    return d != "id" && (y[d] = d3.scale.linear()         
			.domain(d3.extent(splits, function(p) { return +p[d]; }))
			.range([height, 0]));
			
		  }));
		  
		  //!= "race_id" &&
		  //scale.linear()

		  // Add grey background lines for context.
		/*  background = svg.append("g")
		      .attr("class", "background")
		    .selectAll("path")
		      .data(splits)
		    .enter().append("path")
		      .attr("d", path); */

		  // Add blue foreground lines for focus.
		/*  console.log("d= "+'d');
		  console.log("key[5] = "+d3.values(splits[i]));
		  console.log("splitId = "+splits[1].id);*/
		 // if( "d".id==5002)
		 // {
		
		     
		 /*    foreground = svg.append("g")
		      .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")		    
		      .attr("d", path); */
		if($athId != 0)   
		{	
					     
		     foreground2 = svg.append("g")
		       .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")
		     .filter(function(d) { return d.id != $athId })	
		      .attr("d", path); 
		     
		     foreground1 = svg.append("g")
		      .attr("class", "foreground")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")
		     .filter(function(d) { return d.id == $athId })	
		      .attr("d", path);
		}    
		else
		{
			foreground2 = svg.append("g")
		      .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")		    
		      .attr("d", path); 
		}
		  /*    foreground = svg.append("g")
		      .attr("class", "foreground2")		   
		    .selectAll("path")			   	    
		      .data(splits)		     
		    .enter().append("path")
		     .filter(function(d) { return d.id != 5009 })	
		      .attr("d", path); */
		//  }

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
		//console.log(d);
	  return line(dimensions.map(function(p) { /*console.log("s= "+x(p)+" ,||"+y[p]+", tt"+(d[p]));*/ return [x(p), y[p](d[p])]; }));
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
	   
	