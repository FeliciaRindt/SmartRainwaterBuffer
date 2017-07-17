/*  Bachelor Project Creative Technology by Felicia Rindt 
    Capacity History Bar Chart for a smart rainwater buffering system interface.
    This code makes use of the JavaScript library D3 v4 */


function drawChartCitizen() {
    
   var buffersize = 190;
    
    var svg = d3.select(".svg_capacity"),
        margin = {top: 20, right: 20, bottom: 40, left: 55},
        width = +svg.attr("width") - margin.left - margin.right,
        height = +svg.attr("height") - margin.top - margin.bottom,
        g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                        var tooltip = d3.select("body").append("g").attr("class", "toolTip").style("opacity", 0);
    var parseTime = d3.timeParse("%Y-%m-%d %H:%M:%S"); // parse the time for the time scale and axis
    //var parseTime = d3.timeParse("%Y-%m-%dT%H:%M:%S.%LZ"); // parse the time for the time scale and axis
    
    var formatTime = d3.timeFormat("%d %b %Y");

    console.log('width in buf: ' + width);
    var x = d3.scaleBand().rangeRound([0, width], .05);
    var y = d3.scaleLinear().range([height, 0]);
	
	var xAxis = d3.axisBottom()
		.scale(x)
		.tickFormat(d3.timeFormat("%d %b"));
		
	var yAxis = d3.axisLeft()
		.scale(y)
		.ticks(5	);
    
    var counter = 0;
    var translatex = 0;
    var data_agg = new Array();
    var waterlevel_agg = new Array();
    var temp = new Array();
    var buffersize = 190;
    var tickdays = 1;

    d3.json(datahandler, function(error, data) {
		  console.log('Fresh data');
		  console.log(data);
          if (error) throw error; 
            console.log("working dta(1)");
            console.log(data);

          // format the data
          data.forEach(function(d, i) {
              // split the dates in day/month year and time on the other hand
              temp[i] = new Array();
              temp[i] = d["day"].split(" ");
          });
        
        var counter = 0;
        
		
		console.log('Aggr data');
        for (j = 0; j < data.length-1; j++) {
              if (temp[j][0] != temp[j+1][0]) { // If the next date is not the same as the previous date
                  // save this entry, because it's the last entry of the day - to a new array which uses aggregated data
                  data_agg[counter] = new Array();
				  console.log('data[j] = ' + data[j]['day']);
                  data_agg[counter] = data[j];
                  counter++;
              }
        }
		
		console.log(data_agg);
                
        var barWidth = (width-(data_agg.length-1)*4) / (data_agg.length-1);
		
          console.log('data_agg forEach loop');
		  
          data_agg.forEach(function(d) {
			  console.log(d);
              d.day = parseTime(d.day);
              
              //if (d.waterlevel > 190) buffersize = 380; // two neighbourhoods are selected
           
              //d.waterlevel = 100*(d.waterlevel/buffersize); // calculate percentage
              //d.waterlevel = +Math.round(d.waterlevel * 100) / 100; // round to 2 decimals
              
              d.waterlevel = +d.waterlevel;
          });
		  console.log(data_agg);
        
	   
	  console.log('set xdomain');
      x.domain(data_agg.map(function(d) { console.log(d.day); return d.day; }));
      y.domain([0, 200]);
      //y.domain([0, d3.max(data_agg, function(d) { return d.waterlevel })]);
         
	  svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis)
       .selectAll("text")
		.style("text-anchor", "end")
		.attr("dx", "-.8em")
		.attr("dy", "-.55em")
		.attr("transform", "rotate(-80)" );
		
	  svg.append("g")
          .data(data)
          .attr("class", "y axis")
          .call(yAxis)
        .append("text")
          .attr("fill", "#000")
          .attr("y", -3)
          .attr("x",-30)
          .attr("dy", "0.71em")
          .attr("text-anchor", "end")
          .style("z-index", 100)
          .text("(L)");
		
	  svg.selectAll("bar")
		.data(data_agg)
	  .enter().append("rect")
		.style("fill", "#5795b9")
		.attr("x", function(d) {return x(d.day); })
		.attr("width", x.bandwidth()-5)
		.attr("y", function(d) {return y(d.waterlevel); })
		.attr("height", function(d) { return height - y(d.waterlevel); })
		.on("mousemove", function(d){
            tooltip
             .transition()
             .duration(200)
             .style("opacity", .9);
            tooltip
              .attr("class", "toolTip")
              .style("left", d3.event.pageX - 68 + "px")
              .style("top", d3.event.pageY - 75 + "px")
              .style("display", "inline-block")
              .html("Datum: " + (formatTime(d.day)) + "<br>" + "Aantal liters: " + (d.waterlevel) + " L");
        })
      .on("mouseout", function(d){ tooltip.style("display", "none");});
	  
		
	  /*
      var barCapacity = svg.selectAll("g")
      .data(data_agg)
      .enter().append("g")
      .attr("class", "bar")
      .attr("transform", function(data_agg) { 
		  console.log(data_agg);
          translatex += ( (2+barWidth+2));
          return "translate(" + translatex + ",0)";
      })
      // tooltip
      .on("mousemove", function(d){
            tooltip
             .transition()
             .duration(200)
             .style("opacity", .9);
            tooltip
              .attr("class", "toolTip")
              .style("left", d3.event.pageX - 68 + "px")
              .style("top", d3.event.pageY - 75 + "px")
              .style("display", "inline-block")
              .html("Datum: " + (formatTime(d.day)) + "<br>" + "Aantal liters: " + (d.waterlevel) + " L");
        })
      .on("mouseout", function(d){ tooltip.style("display", "none");});
        
      barCapacity.append("rect")
      .data(data_agg)
      .attr("class", "bar")
      .attr("y", function(d) {  return y(d.waterlevel) + margin.top; })
      .attr("width", barWidth)
      .attr("height", function(d) { return height - y(d.waterlevel); }) // otherwise the graph is upside down

      // Draw the x axis
      g.append("g")
          .attr("class", "x axis")
          .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x).ticks(d3.timeDay.every(tickdays)).tickFormat(d3.timeFormat("%d %b")))
            .selectAll("text")  
            .style("text-anchor", "begin")
            .attr("x", barWidth/2);

      // Draw the y axis
      g.append("g")
          .data(data)
          .attr("class", "y axis")
          .call(d3.axisLeft(y))
        .append("text")
          .attr("fill", "#000")
          .attr("y", -3)
          .attr("x",-30)
          .attr("dy", "0.71em")
          .attr("text-anchor", "end")
          .style("z-index", 100)
          .text("(L)")
    */
    });
}