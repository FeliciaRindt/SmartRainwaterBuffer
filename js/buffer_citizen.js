/*  Bachelor Project Creative Technology by Felicia Rindt 
    Buffer chart for a smart rainwater buffering system interface.
    This code makes use of the JavaScript library D3 v4 */


function drawBuffer() {
    
    var buffersize = 190;

    var bufferchart = d3.select(".svg_buffer"),
        margin = {top: 10, right: 10, bottom: 10, left: 48},
        width = +bufferchart.attr("width") - margin.left - margin.right,
        height = +bufferchart.attr("height") - margin.top - margin.bottom,
        bufferg = bufferchart.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var barWidthBuffer = width/3;    

    var x = d3.scaleBand().rangeRound([0, barWidthBuffer]).padding(0.1),
        y = d3.scaleLinear().rangeRound([height, 25]);

    var width = 180;

    // display buffer png image
    var imgs = bufferchart.select("g").selectAll("image")
            .data([0])
            .enter()
            .append("bufferchart:image")
            .attr("xlink:href", "../images/buffer.png")
            .attr("x",0)
            .attr("y", -10)
            .attr("width", 220)
            .attr("height", 220);

    var tooltip1 = d3.select("body").append("g").attr("class", "toolTipBuffer");
    var tooltip2 = d3.select("body").append("g").attr("class", "toolTip2");

//    var tooltip2 = d3.select("body").append('div').attr('class', 'toolTip2');
  //      tooltip2.append('div')
    //        .attr('class', 'rainfall'); 

    var rainfall = 0;
    
    d3.json(datahandler_current, function(error, data) {
              console.log(data);
              if (error) throw error; 
                console.log(rainfall);
             // rainfall = data[data.length - 1]["waterlevel"];
                      rainfall = 190;

            
      y.domain([0, 1]);

      // draw y axis
      bufferg.append("g")
          .attr("class", "y axis")
          .call(d3.axisLeft(y).ticks(10).tickFormat(function(d) { return d * 100; }))
      .append("text")
          .attr("fill", "#000")
          .attr("transform", "rotate(-90)")
          .attr("y", -44)
          .attr("x", -20)
          .attr("dy", "0.71em")
          .attr("text-anchor", "end")
          .text("Waterniveau (%)")
        

      bufferg.selectAll(".bar")
        .data(data)
        .enter().append("rect")
          .attr("class", "bar")
          .attr("x", 10)
          .attr("y", function(d) { return y(rainfall/buffersize)})
          .attr("width", 200)
          .attr("height",  function(d) { return height - y(rainfall/buffersize)})
                
        // tooltip
        tooltip1
            .attr("class", "toolTipBuffer")
                  .style("display", "block")
                  .style("opacity", .9)
                  .style("left", $('.bar').offset().left + 30 + "px")
                  .style("top", $('.bar').offset().top - 70 + "px")
                  .html(" Huidig volume: " + rainfall + " L <br> Gevuld voor: " + Math.round(100*(rainfall/buffersize)*100)/100 + "%");
       
        $.getJSON('data-future.php', function(future) { 
            
            //var volumepercentage2 = (+rainfall + +future)/buffersize;
           var volumepercentage2 = rainfall/buffersize; 
        var linewidth = +bufferchart.attr("width");

        var predection = d3.select("g")
            .append('line')
            .attr('class','prediction')
            .attr('x1', margin.left - 48)
            .attr('y1', y(volumepercentage2))
            .attr('x2', linewidth - 120)
            .attr('y2', y(volumepercentage2))
            .attr("stroke-width",2)
            .attr("stroke", "#345972");
            //.style("display", "none");

        tooltip2    
            .attr("class", "toolTip2")
            .style("display", "block")
                  .style("left", $('.prediction').offset().left + 230 + "px")
                  .style("top", $('.prediction').offset().top - 30 + "px")
                  //.html(" Gepland volume <br> over 2 uur: " + (+rainfall + +future) + " L " );  
            .html(" Gepland volume <br> over 2 uur: "  +rainfall + " L " );  
            
        });
        
        
       
        
        // define size of the bar
        //var bar_top = $('.bar').offset().top - 50;
        //var bar_left = $('.bar').offset().left + 15;

        //tooltip1.style('top', bar_top+'px').style('left', bar_left+'px');
        //tooltip2.select('rainfall').html("Volume na geplande <br> leegloop: " + rainfall +" L");
        //tooltip2.style('display', 'block');

        // var line_top = $('.prediction').offset().top-23;
        //var line_left = $('.prediction').offset().left-5;

        //tooltip2.style('top', line_top+'px').style('left', line_left+linewidth+'px');  
    });
}