<!DOCTYPE html>
<html lang="en">
  <head>
      
      <!--  Bachelor Project Creative Technology by Felicia Rindt 
            index.php page for a smart rainwater buffering system interface.
            This code makes use of the JavaScript library D3 v4, Bootstrap v3.3.7, jQuery and Moment.js and content from www.daterangepicker.com  -->
      
      
      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>Tonnie, the smart rainwater buffering system</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
      
    <!-- Leaflet code -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
      
    <!-- JQuery & Moment.js -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
      
    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
      
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
      
    <!-- D3 style -->
    <link href="css/d3.css" rel="stylesheet">
      
    <!-- Custom styles for this template -->
    <link href="css/dashboard_citizen.css" rel="stylesheet">
    
  </head>
    
  <body style="background-color: #fff;"> <!-- custom background color -->
      
<!-- load the d3.js library -->    	
<script src="https://d3js.org/d3.v4.min.js"></script>

    <nav class="navbar navbar-inverse navbar-fixed-top shadow citizen_navbar">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="color: #fff; font-size: 30pt" href="#">TONNIE</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
              <!-- Date display -->
              <li><a style="color: #fff; font-size: 20pt" href="#">
                  <script>
                  var today = new Date();
                  var monthNames = [ "januari", "februari", "maart", "april", "mei", "juni",
                                    "juli", "augustus", "september", "oktober", "november", "december" ];
                  var weekDays = [ "Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag" ];
                  var date = (weekDays[today.getDay()])+' '+today.getDate()+' '+(monthNames[today.getMonth()])+' '+today.getFullYear();
                  document.write(date);
                  </script>
                  </a></li>
              <!-- Status display -->
              <li><div id="status">
                  
                  <script>
                  var images = ["images/Priority-critical.png", "images/Priority-error.png", "images/Priority-warning.png", "images/Priority-notification.png", "images/Priority-OK.png"]; // array with the paths to the priority (status) images. index corresponds to priority number (0, 1, 2, 3, 4)
                  var statusnames = ["Kritiek", "Error", "Waarschuwing", "Melding", "OK"]; // array with priority (status) names
                      
                  $.getJSON('data-status.php', function(index) {  
                    document.getElementById('status').innerHTML = "<img src=" + images[index] + " width='40px' style='margin-top:-5px; margin-left: -5px'> Status: " + statusnames[index];   
                  });   
                
                  </script>
                  </div></li>
          </ul>
        </div>
      </div>
    </nav>
    
    <div class="row">
        <div class="col-md-7 whitebox" style="height: 300px;margin-bottom:30px;margin-right:0px;">
            
<!--            <div class="row">-->
                
                <div class="col-md-8" style="margin-right: -40px; ">
                    <h4 style="margin-bottom:0px;margin-top:0px;">Huidige vullingsgraad</h4>
                    <h4>Maximale capaciteit: 190L</h4>
                    <div id="bufferid">
                    <svg class="svg_buffer" width="350" height="230"></svg>
                    <script src="js/buffer_citizen.js"></script>
                    </div>
                </div>
                <div class="col-md-5" >
                    
                    <h4 style="margin-top:0px;">Automatische waterafvoer</h4>
                        <form id="changeDefaultValve" action="http://regenbuffer.student.utwente.nl/app.php/buffers/1/standardvalves" target="frame" method="post">
                        <select name="valve" id="defaultValve" style="padding:5px;margin-left:10px;">
                            <option value="1">Riool</option>
                            <option value="2">Tuin</option>
                        </select>
                        </form>
                    <hr />
                    
                    <form action="http://regenbuffer.student.utwente.nl/app.php/buffers/1/discharges" target="frame" method="post">
                        <h4 >Handmatige waterafvoer</h4>
                            <select name="valve" style="padding:5px;margin-left:10px;">
                                <option value="1">Riool</option>
                                <option value="2">Tuin</option>
                            </select>
                        <h4 >Stel hoeveelheid in</h4>
                        <div id="controlid">
                                <input id="amountLoseWater" name="amount" type="number" value="0" min="0" max="190" style="width:60px;margin-left:10px;padding:5px;" /> L
                                <button type="submit" name="button" value="open" style="background:transparent; border: none;"><img src="images/button_open.png" width="60px"></button>
                                <button type="submit" name="button" value="annuleer" style="background:transparent; border: none;"><img src="images/button_annuleer.png" width="60px"></button>
                        </div>
                    </form>
                </div>
                
            
<!--        </div>-->
        </div>
    <div class="col-md-4 whitebox" style="height:300px;margin-bottom:0px;margin-left:0px;">
        
        <h4 style="margin-bottom:5px;margin-top:0px;">Historie waterafvoer naar de tuin</h4>
        <div style="margin-left:10px;">
                    Selecteer een tijdsperiode: <input class="datepicker" type="text" id="gardenHistoryRange" name="daterange" value="" style="width:175px; text-align: center;"/>
                </div>
         
        <div id="gardenHist">
                <svg class="svg_garden" width="450" height="230" style="margin-left:55px;overflow:visible;margin-top:25px;"></svg> 
                <script src="js/history_garden_dis.js" ></script>
        </div>
        
    </div>
      </div> 
      <div class="row">
          
          <div class="col-md-7 whitebox"  style="margin-top:0px;margin-right:0px">
              
              <h4 style="margin-bottom:0px;margin-top:0px;">Verwachte neerslag komende 2 uur</h4>
              <div id="rainid">
              <svg class="svg_rain" width="650" height="250"></svg>
              <script src="js/rainforecast_citizen.js"></script>
              </div>
          </div>
          
          <div class="col-md-4 whitebox" style="margin-top:0px;margin-left:0px;">
              
              <h4 style="margin-bottom:5px;margin-top:0px;">Historie gebufferd regenwater</h4>
                <div style="margin-left:10px;">
                    Selecteer een tijdsperiode: <input class="datepicker" type="text" id="rainwaterHistoryRange" name="daterange" value="" style="width:175px; text-align: center;"/>
              </div>

                <div id="capid">
                <svg class="svg_capacity" width="450" height="230" style="margin-left:55px;overflow:visible;margin-top:25px;"></svg> <!-- create svg for the first time the chart is drawn -->
                
                <script src="js/bufferhistory_citizen.js"></script>

                <script type="text/javascript">
                    
                // get the date of today
                var today = new Date();
                var formatToday = d3.timeFormat("%Y-%m-%d");    

                // data file handler php, define default selection
                var datahandler = "http://regenbuffer.student.utwente.nl/app.php/buffers?start=2017-06-04&end=2017-06-10&neighbourhood=Velve-Lindenhof";
                var datahandler_current = "http://regenbuffer.student.utwente.nl/app.php/buffers?start=" + formatToday(today) + "&end=" + formatToday(today) + "&neighbourhood=Velve-Lindenhof"; 
                var datahandler_flow = "http://regenbuffer.student.utwente.nl/app.php/buffers/1/waterflows?start=2017-06-04&end=2017-06-10";

                var data_part = datahandler.split("?"); // split data for further handling
                var data_part_current = datahandler_current.split("?"); // split data for further handling
                var data_part_flow = datahandler_flow.split("?");
                var data_part_split = data_part[1].split("&") // split the url in the start [0], end [1] and neighbourhood [2] statements
                var data_part_split_current = data_part_current[1].split("&") // split the url in the start [0], end [1] and neighbourhood [2] statements
                var data_part_split_flow = data_part_flow[1].split("&"); // split the url in the start [0], end [1]
                
                $('#amountLoseWater').keyup(function() {
                    if($(this).val()<0) $(this).val("0");
                    if($(this).val()>190) $(this).val("190");
                });
                
                
                // initialize date range picker
                var firstRun = true;
                    
                $('.datepicker').on('change', function(e) {
                   var selectedDatePicker = e.target.id;
                   var bothDates = $(this).val();
                   bothDates = bothDates.split(" - ");
                   //console.log(bothDates);
                   // DENNIS: JE MOET VERSCHILLENDE VALUES AT DB KUNNEN OPVRAGEN, FIX 
                   //database = (selectedDatePicker=="rainwaterHistoryRange") ? "water_level" : "outflow_garden";
                   //console.log('database: ' + database);
                   
                    if(selectedDatePicker=="rainwaterHistoryRange") {
                      // update chart
                      console.log("rainwater history updater");
                      datahandler = data_part[0] + "?start=" + bothDates[0] + "&end=" + bothDates[1] + "&neighbourhood=Velve-Lindenhof";
                      console.log(datahandler);
                      d3.select(".svg_capacity").remove(); // remove current svg for updating purposes
                      var newSvgCap2 = document.getElementById("capid");
                      newSvgCap2.innerHTML += '<svg class="svg_capacity" width="450" height="230" style="margin-left:55px;overflow:visible;margin-top:25px;"></svg>'; // add the svg again
                      drawChartCitizen(); // update chart 
                      
                   } else if (selectedDatePicker=="gardenHistoryRange") {
                      console.log("garden history updater");
                      datahandler_flow = data_part_flow[0] + "?start=" + bothDates[0] + "&end=" + bothDates[1] + "&neighbourhood=Velve-Lindenhof";
                     console.log(datahandler_flow);
                     d3.select(".svg_garden").remove(); // remove current svg for updating purposes
                      var newSvgCap3 = document.getElementById("gardenHist");
                      newSvgCap3.innerHTML += '<svg class="svg_garden" width="450" height="230" style="margin-left:55px;overflow:visible;margin-top:25px;"></svg>'; // add the svg again
                      drawGarden(); // update chart 
                   }
                });
                
                $(function() {
                    $('input[name="daterange"]').daterangepicker(
                        {
                        locale: {
                          format: 'DD-MM-YYYY'
                        },
                          startDate: '04-06-2017',
                          endDate: '10-06-2017'
					})
				});
                
                $("#defaultValve").change(function() {
                     $("#changeDefaultValve").submit();
                });

                $(document).ready(function(){
                    // call draw functions when DOM is ready
                    $.get("http://regenbuffer.student.utwente.nl/app.php/buffers/1/standardvalves", function(d) {
                            console.log('defaultValve: ' + d);
                            $("#defaultValve").children('option[value=' + d + ']').prop("selected", true);
                    });
                    //drawChartCitizen();
                    drawBuffer();
                    //drawGarden();
                    $("#rainwatterHistoryRange").trigger("change");
                    $("#gardenHistoryRange").trigger("change");
                    //drawDischarge();
                    
                });

                </script>
                </div>
              </div>
          </div>
      
     
<footer>
  <p>2017, Creative Technology</p>
</footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
    <!-- <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>-->
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
      <iframe name="frame" style="display:none"></iframe>
  </body>
</html>
