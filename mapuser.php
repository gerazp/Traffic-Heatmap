<!DOCTYPE html>  
 <html>  
      <head>  
      
        
           <title>ANALIZE</title>  
          
           <style>
     body, html { background-image: url("./images/city1.jpg"); background-size: cover;background-repeat:no-repeat;margin:0; padding:0; height:75%;}    
          
      #map { height:70%; }
      .leaflet-container {
        background: rgba(0,0,0,.8) !important;
      }
    </style>
    <link rel="stylesheet" href="newstyle.css"> 
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
    <script src="./heatmap/build/heatmap.js"></script>
    <script src="./heatmap/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>

           
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
           <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
           
           <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      </head>  
      <body> 
      <button class="testheat" onclick="window.location.href='indexuser.php'">Back to user Dashboard</button>
      
      <div id="map"></div>
      
      <br /><br />  
      <div class="container" style="width:900px;">  
           
           <div  class="col-md-3" > 
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" /> 

           </div> 


           <div class="col-md-3">  
                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" /> 

           </div>  
           <div class="col-md-5">  
                <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />  
           </div>  
           <div style="clear:both"></div>                 
           <br />  
           <div id="order_table">   </div>
                <table class="table table-bordered">  
                     <tr>  
                            
                     </tr>  
               
                </table>  
            


      </div> 
    
     
        
           <script>  
      $(document).ready(function(){  
           $.datepicker.setDefaults({  
                dateFormat: 'yy-mm-dd'   
           });  
           $(function(){  
                $("#from_date").datepicker();  
                $("#to_date").datepicker();  
           });  
           $('#filter').click(function(){  
                var from_date = $('#from_date').val();  
                var to_date = $('#to_date').val();  
                if(from_date != '' && to_date != '')  
                {  
                  
                     $.ajax({  
                          url:"server.php",  
                          method:"POST",  
                          data:{from_date:from_date, to_date:to_date},  
                          success:function(data)  
                          {  
                               $('#order_table').html(data);  
                                   //erwthma a
                                
                              var myjprest=JSON.parse(jprest);
                              var myjarray=JSON.parse(jarray);
                              var myjprestnull=JSON.parse(jprestnull);
                              var myjday=JSON.parse(jday);
                              var myjhour=JSON.parse(jhour);
                              
                            
                             
                              //grafhma
                              google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Activity');
        data.addColumn('string', 'Percentage');
        data.addColumn('string', 'Most frequent day');
        data.addColumn('string', 'Most frequent hour');
        data.addRows([
          [myjarray[0],myjprest[0],myjday[0],myjhour[0]],
          [myjarray[1],myjprest[1],myjday[1],myjhour[1]],
          [myjarray[2],myjprest[2],myjday[2],myjhour[2]],
          [myjarray[3],myjprest[3],myjday[3],myjhour[3]],
          [myjarray[4],myjprest[4],myjday[4],myjhour[4]],
          [myjarray[5],myjprest[5],myjday[5],myjhour[5]],
          [myjarray[6],myjprest[6],myjday[6],myjhour[6]],
          [myjarray[7],myjprest[7],myjday[7],myjhour[7]]
        ]);
          
        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
      }          
      
    
     
                               //erwtrhma XARTES
       var myjlat=JSON.parse(jlat);
        var myjlon=JSON.parse(jlon);  
        var jsonArr = [];
        for (var i = 0; i < myjlat.length; i++) {
          jsonArr.push({    
            x: myjlat[i],
            y: myjlon[i],
            count: i*50
         });
         var testData = {
          data: jsonArr
        };
        
      }
        
        var baseLayer = L.tileLayer(
          'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
            maxZoom: 18
          }
        );
     
        var cfg = {
          // radius should be small ONLY if scaleRadius is true (or small radius is intended)
          "radius": 0.0005,
          "maxOpacity": .8, 
          // scales the radius based on map zoom
          "scaleRadius": true, 
          // if set to false the heatmap uses the global maximum for colorization
          // if activated: uses the data maximum within the current map boundaries 
          //   (there will always be a red spot with useLocalExtremas true)
          "useLocalExtrema": true,
          // which field name in your data represents the latitude - default "lat"
          latField: 'x',
          // which field name in your data represents the longitude - default "lng"
          lngField: 'y',
          // which field name in your data represents the data value - default "value"
          valueField: 'count'
        };
        var heatmapLayer = new HeatmapOverlay(cfg);
        var map = new L.Map('map', {
          center: new L.LatLng(38.246242, 21.7350847),
          zoom: 16,
         
        });
        map.addLayer(baseLayer);
        heatmapLayer.setData(testData);
       
        map.addLayer(heatmapLayer);
        layer = heatmapLayer;
                          }
                     });  
                }  
                else  
                {  
                     alert("Please Select Date");  
                }  
           });
           
         
       
 
      });  
  
      </script> 
     
  

     <div id="table_div"></div>
 
      </body>  
 </html> 