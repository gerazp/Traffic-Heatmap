
<!DOCTYPE html>

<head>

<style>
      body, html { background-image: url("./images/city1.jpg"); background-size: cover;margin:0; padding:0; height:0%;}  
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
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
  
          <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
          <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

          
         

</head>
<body>
<button class="export" name="export" onclick="myFunction()">Export</button>

<script>
function myFunction() {
 
    window.location.href="server.php?export='1'";
}
</script>


<button class="testheatadmin" onclick="window.location.href='indexadmin.php'">Back to admin Dashboard</button>
<div class="container_map">
<div id="map"></div>
<div id="order_table">   </div>


<br /><br />

 <div class="col-md-4">
 <br /><br />
 
 <form method="post" id="multiple_select_form1">
    <h2 style="color:Aqua"><b>Year</b></h2>
    <select  name="Year" id="Year" class="form-control selectpicker" data-live-search="true" multiple required>
     <option value="2000">2000</option>
     <option value="2001">2001</option>
     <option value="2002">2002</option>
     <option value="2003">2003</option>
     <option value="2004">2004</option>
     <option value="2005">2005</option>
     <option value="2006">2006</option>
     <option value="2007">2007</option>
     <option value="2008">2008</option>
     <option value="2009">2009</option>
     <option value="2010">2010</option>
     <option value="2011">2011</option>
     <option value="2012">2012</option>
     <option value="2013">2013</option>
     <option value="2014">2014</option>
     <option value="2015">2015</option>
     <option value="2016">2016</option>
     <option value="2017">2017</option>
     <option value="2018">2018</option>
     <option value="2019">2019</option>
     <option value="2020">2020</option>
     <option value="ALL">SELECT ALL</option>
    </select>
</form>
    <br /><br />
   
    <form method="post" id="multiple_select_form2">
    <h2 style="color:yellow"><b>Month</b></h2>
<select name="Month" id="Month" class="form-control selectpicker" data-live-search="true" multiple required>
 <option value="1">January</option>
 <option value="2">February</option>
 <option value="3">March</option>
 <option value="4">April</option>
 <option value="5">May</option>
 <option value="6 ">June </option>
 <option value="7">July</option>
 <option value="8">August</option>
 <option value="9">September</option>
 <option value="10 ">October </option>
 <option value="11 ">November </option>
 <option value="12">December</option>
 <option value="ALL">SELECT ALL</option>
</select>
</form>
<br /><br />
<form method="post" id="multiple_select_form3">
<h2 style="color:Red"><b>Day</b></h2>
    <select name="Day" id="Day" class="form-control selectpicker" data-live-search="true" multiple required>
     <option value="Monday">Monday</option>
     <option value="Tuesday">Tuesday</option>
     <option value="Wednesday">Wednesday</option>
     <option value="Thursday ">Thursday</option>
     <option value="Friday">Friday </option>
     <option value="Saturday ">Saturday </option>
     <option value="Sunday ">Sunday </option>
     <option value="ALL">SELECT ALL</option>
    </select>
    </form>
  
    <br /><br />
    <form method="post" id="multiple_select_form4">
    <h2 style="color:Coral "><b>Hour</b></h2>
    <select name="Hour" id="Hour" class="form-control selectpicker" data-live-search="true" multiple required>
     <option value="0">00:00</option>
     <option value="1">01:00</option>
     <option value="2">02:00</option>
     <option value="3">03:00</option>
     <option value="4">04:00</option>
     <option value="5">05:00</option>
     <option value="6">06:00</option>
     <option value="7">07:00</option>
     <option value="8">08:00</option>
     <option value="9">09:00</option>
     <option value="10">10:00</option>
     <option value="11">11:00</option>
     <option value="12">12:00</option>
     <option value="13">13:00</option>
     <option value="14">14:00</option>
     <option value="15">15:00</option>
     <option value="16">16:00</option>
     <option value="17">17:00</option>
     <option value="18">18:00</option>
     <option value="19">19:00</option>
     <option value="20">20:00</option>
     <option value="21">21:00</option>
     <option value="22">22:00</option>
     <option value="23">23:00</option>
     <option value="ALL">SELECT ALL</option>
    </select>
    </form>
    <br /><br />
    
    <br />
  
   <form method="post" id="multiple_select_form5">
   <h2 style="color:Chartreuse"><b>Activity</b></h2>
    <select name="Activity" id="Activity" class="form-control selectpicker" data-live-search="true" multiple required>
     <option value="WALKING">WALKING</option>
     <option value="IN_VEHICLE">IN_VEHICLE</option>
     <option value="ON_BICYCLE">ON_BICYCLE</option>
     <option value="ON_FOOT">ON_FOOT</option>
     <option value="RUNNING">RUNNING</option>
     <option value="STILL">STILL</option>
     <option value="TILTING">TILTING</option>
     <option value="UNKNOWN">UNKNOWN</option>
     <option value="ALL">SELECT ALL</option>
    </select>
    </form>
    <br /><br />
    
    <div class="col-md-4">
    <button type="button" name="Search" id="Search" class="btn btn-info"> Search </button>
    </div>
    </div>
   

<script>  
    $(document).ready(function(){  
    
         $('#Search').click(function(){  
               var Year=$('#Year').val();
               var Month=$('#Month').val();
               var Day=$('#Day').val();
               var Hour=$('#Hour').val();
               var Activity=$('#Activity').val();
               console.log(Year);
               console.log(Month);
               console.log(Day);
             
               if(Year != null && Month != null && Day != null && Hour != null && Activity != null) {
       
                    $.ajax({  
                          url:"server.php",  
                          method:"POST",  
                          data:{Year:Year, Month:Month,Day:Day, Hour:Hour,Activity:Activity},  
                          success:function(data)  
                          {  
                               $('#order_table').html(data);  
                               var myjlat=JSON.parse(jlat);
                              var myjlon=JSON.parse(jlon);
                              console.log(myjlat);
                              console.log(myjlon);
        
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
          lngField: 'y',
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
     }else{  
                     alert("Please Select All the Fields");  
      }  
     });
});
     </script> 
    
 
    
     </body>  
</html> 