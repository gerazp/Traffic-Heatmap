<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="newstyle.css">  
    <link rel="stylesheet" href="drawstyle.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <link rel="stylesheet" href="./src/leaflet-areaselect.css" />
</head>
<body>
    <div id="map"></div> 
    <form method="post" action="covermap.php">
    <div id="result">
        <div class="left">
            South west:<br>
            <input type="text" name="panw" class="sw"><br>
            
            North east:<br>
            <input type="text" name="katw" class="ne">
            
            <button name="remove" id="remove">Remove</button>            
        </div>
    </div>
</form>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script src="./src/leaflet-areaselect.js"></script>
    <script>
        // initialize map
        
        var baseLayer = L.tileLayer(
          'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
            maxZoom: 18
          }
        );
        var map = new L.Map('map', {
          center: new L.LatLng(38.246242, 21.7350847),
          zoom: 16,
         
        });
        map.addLayer(baseLayer);


        
        
        var areaSelect = L.areaSelect({width:200, height:250});
        areaSelect.on("change", function() {
            var bounds = this.getBounds();
            $("#result .sw").val(bounds.getSouthWest().lat + ", " + bounds.getSouthWest().lng);
            $("#result .ne").val(bounds.getNorthEast().lat + ", " + bounds.getNorthEast().lng);
           
        });
        areaSelect.addTo(map);
       

        
    </script>  
    <button onclick="window.location.href='indexuser.php'" class="logout" id="finish">Finish</button>
    
</body>