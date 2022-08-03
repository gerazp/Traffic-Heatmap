<?php
//Erwtima 1.1
//gia na min emfanizontai warnings
error_reporting(E_ERROR | E_PARSE);
$db = mysqli_connect('localhost', 'root', '', 'database') or die("could not connect to database");
$array = array("WALKING", "IN_VEHICLE","ON_BICYCLE","ON_FOOT","RUNNING","STILL","TILTING","UNKNOWN");
//psaxnoume tis aparaitites plirofories pou 8a emfanistous ston admin
for ($i = 0; $i < 9; $i++) {
  $sql1= "SELECT COUNT(*) FROM userjson  " ;
  $result = mysqli_query($db, $sql1);
  $rows=mysqli_fetch_array($result);
  mysqli_free_result($result);
  $countactivity=$rows[0];
}
for ($i = 0; $i <8; $i++) {
   $sql = "SELECT COUNT(activity)  FROM userjson WHERE  activity='$array[$i]'   " ;
       
       $result = mysqli_query($db, $sql);
       $rows=mysqli_fetch_array($result);
       mysqli_free_result($result);
       $count[$i]=$rows[0];
      
}
//a8roizoume ola ta count pou vrikame
$counter =$count[0]+$count[1]+$count[2]+$count[3]+$count[4]+$count[5]+$count[6]+$count[7];
$countnull=$countactivity-$counter;
$counter=$counter+$countnull;
if($counter != 0){   
for ($i = 0; $i < 8; $i++){
        $pr=($count[$i]/$counter)*100;
       $prest[$i]=$pr;
      
   }
}
//ERWTHMA 1.2
$sql="SELECT id,username FROM user";
$result = mysqli_query($db, $sql);
$i=0;
while($row = mysqli_fetch_array($result)){
   $id[$i]=$row[0];
   $onomata[$i]=$row[1];
   $sql="SELECT COUNT(*) FROM userjson WHERE id='".$id[$i]."' ";
   $result2 = mysqli_query($db, $sql);
   $rows1=mysqli_fetch_array($result2);
   $counteggrafwn[$i]=$rows1[0];
   $i=$i+1;
}
  
 
   
//ERWTHMA 1.3
for ($i = 1; $i < 13; $i++){
   $sql="SELECT COUNT(*) FROM userjson WHERE month= '$i'  ";
   
   $result = mysqli_query($db, $sql);
   $rows=mysqli_fetch_array($result);
   mysqli_free_result($result);
   $countmonth[$i-1]=$rows[0];
}
//erwtham 1.4
$days = array("Monday", "Tuesday ","Wednesday ","Thursday","Friday","Saturday","Sunday ");
for ($i = 0; $i < 7; $i++){
   $sql="SELECT COUNT(*) FROM userjson WHERE day= '".$days[$i]."'  ";
   $result = mysqli_query($db, $sql);
   $rows=mysqli_fetch_array($result);
   mysqli_free_result($result);
   $countday[$i]=$rows[0];
}
//erwthma 1.5
$wres=array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
for ($i = 0; $i < 24; $i++){
   $sql="SELECT COUNT(*) FROM userjson WHERE hour = '$i'  ";
   $result = mysqli_query($db, $sql);
   $rows=mysqli_fetch_array($result);
   mysqli_free_result($result);
   $counthours[$i]=$rows[0];
}
//erwthma 1.6
$timenowsecs = time(); //vriskoume tin akrivi xroniki stigmi pou hr8e to request
$year = date('Y',$timenowsecs); //to metatrepoume se years
$j=0;
$startingyear=$year-20; // asxoloumaste gia ta teleutaia 20 xronia
for ($i = $startingyear; $i <=$year; $i++){
   $sql="SELECT COUNT(*) FROM userjson WHERE year = '$i'  ";
   $result = mysqli_query($db, $sql);
   $rows=mysqli_fetch_array($result);
   $countyears[$j]=$rows[0];
   $years[$j]="".$i; // pername se enan pinaka olous tous xronous
   $j=$j+1;
 
  
   mysqli_free_result($result);
}
    //pername ta apotelesmata stin javascript gia na ftiaksoume tous antistoixous pinakes
    $en=json_encode($prest);
    echo "<script type='text/javascript'> var jprest = '$en'  ;</script>";
    $prestnull=($countnull/$counter)*100;
    $en=json_encode($prestnull);
    echo "<script type='text/javascript'> var jprestnull = '$en'  ;</script>";
    $en=json_encode($countmonth);
    echo "<script type='text/javascript'> var jcountmonth = '$en'  ;</script>";
    $en=json_encode($counteggrafwn);
    echo "<script type='text/javascript'> var jcounteggrafwn = '$en'  ;</script>";
    $en=json_encode($onomata);
    echo "<script type='text/javascript'> var jonomata = '$en'  ;</script>";
    $en=json_encode($countday);
    echo "<script type='text/javascript'> var jcountday = '$en'  ;</script>";
    $en=json_encode($counthours);
    echo "<script type='text/javascript'> var jcounthour = '$en'  ;</script>";
    $en=json_encode($countyears);
    echo "<script type='text/javascript'> var jcountyears = '$en'  ;</script>";
    $en=json_encode($array);
    echo "<script type='text/javascript'> var jarray = '$en'  ;</script>";
    $en=json_encode($years);
    echo "<script type='text/javascript'> var jyears = '$en'  ;</script>";
    $en=json_encode($wres);
    echo "<script type='text/javascript'> var jwres = '$en'  ;</script>";

?>
<!DOCTYPE html>

<head>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="newstyle.css">  
</head>
<body>
<button class="delete" name="delete" onclick="myFunction()">Delete database</button>

<button class="analize" onclick="window.location.href='mapadmin.php'">Data Analysis</button>
<button class="logout" onclick="window.location.href='logout.php'">Logout</button>


<script type="text/javascript">
//ftiaxnoume tous pinakes mesw google charts                
                 var myjprest=JSON.parse(jprest);
                 var myjprestnull=JSON.parse(jprestnull);
                 var myjcountmonth=JSON.parse(jcountmonth);
                 var myjcounteggrafwn=JSON.parse(jcounteggrafwn);
                 var myjonomata=JSON.parse(jonomata);
                 var myjcountday=JSON.parse(jcountday);
                 var myjcounthour=JSON.parse(jcounthour);
                 var myjcountyears=JSON.parse(jcountyears);
                 var myjarray=JSON.parse(jarray);
                 var myjyears=JSON.parse(jyears);
                 var myjwres=JSON.parse(jwres);
            
                google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Activity", "Percentage", { role: "style" } ],
        ["WALKING",myjprest[0]/100, "silver"],
        ["IN_VEHICLE",myjprest[1]/100, "#b87333"],
        ["ON_BICYCLE",myjprest[2]/100, "silver"],
        ["ON_FOOT",myjprest[3]/100, "gold"],
        ["RUNNING",myjprest[4]/100, "color: #e5e4e2"],
        ["STILL", myjprest[5]/100, "silver"],
        ["TILTING",myjprest[6]/100, "gold"],
        ["UNKNOWN",myjprest[7]/100 , "color: #e5e4e2"],
        ["NULL", myjprestnull/100, "color: #e5e4e2"]
      ]);
      var view = new google.visualization.DataView(data);
     
      var options = {
        title: "PERCENTAGE PER ACTIVITY",
        width: 600,
        height: 500,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        vAxis: {
                
                format: 'percent',
                  title: 'Percentage',
                  
      },
    };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  };
      
//MHNESS
    </script>
    <script type="text/javascript">
  google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day');
        data.addColumn('string', 'data/day');
        data.addRows([
          ["Monday" ,myjcountday[0]],
          ["Tuesday",myjcountday[1]],
          ["Wednesday",myjcountday[2]],
          ["Thursday",myjcountday[3]],
          ["Friday",myjcountday[4]],
          ["Saturday",myjcountday[5]],
          ["Sunday",myjcountday[6]]
        ]);
          
        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true, width: '640px', height: '400px'});
      }          
//MERES
</script>

    <script type="text/javascript">
  google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('string', 'data/mmonth');
        data.addRows([
          ["January" ,myjcountmonth[0]],
          ["February",myjcountmonth[1]],
          ["March",myjcountmonth[2]],
          ["April",myjcountmonth[3]],
          ["May",myjcountmonth[4]],
          ["June",myjcountmonth[5]],
          ["July",myjcountmonth[6]],
          ["August",myjcountmonth[7]],
          ["September",myjcountmonth[8]],
          ["October ",myjcountmonth[9]],
          ["November",myjcountmonth[10]],
          ["December",myjcountmonth[11]]
        ]);
          
        var table = new google.visualization.Table(document.getElementById('table_div2'));
        table.draw(data, {showRowNumber: true, width: '640px', height: '400px'});
      }          
</script>

    
    <script type="text/javascript">
    //WRES
  google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'hour');
        data.addColumn('string', 'data per hour');
        //vazoume grammes gia ka8e wra tis imeras
      for (var i = 0; i < myjwres.length; i=i+1) {
        data.addRows([
          [myjwres[i],myjcounthour[i]]
        
        ]);
      }
        var table = new google.visualization.Table(document.getElementById('table_div3'));
        table.draw(data, {showRowNumber: false, width: '640px', height: '400px'});
      }  
      </script>      
      <script type="text/javascript">
//XRONIA
google.charts.load('current', {'packages':['table']});
  google.charts.setOnLoadCallback(drawTable);
  function drawTable() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Years');
    data.addColumn('string', 'data per year');
    //vazoume grammes gia ka8e xrono
    for (var i = 0; i < myjyears.length; i=i+1) {
    data.addRows([
      [myjyears[i],myjcountyears[i]]
      ]);
  }
    var table = new google.visualization.Table(document.getElementById('table_div4'));
    table.draw(data, {showRowNumber: false, width: '640px', height: '400px'});
  }  
  </script>   

  <script type="text/javascript">
  
  //XRHSTES
  google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'USER');
        data.addColumn('string', 'data/user');
    var i=0;
    for (var i = 0; i < myjcounteggrafwn.length; i=i+1) {
        data.addRows([
         
          [myjonomata[i] ,myjcounteggrafwn[i]]
          
        ]);
        
      }
        var table = new google.visualization.Table(document.getElementById('table_div5'));
        table.draw(data, {showRowNumber: true, width: '640px', height: '400px'});
      }    
</script>    
<div class="flex">
<div class="div" id="table_div"></div>
<div class="div2" id="table_div2"></div>
</div>
<div class="flex">
<div class="div" id="table_div3"></div>
<div class="div2" id="table_div4"></div>
</div>
<div class="flex">
<div class="div" id="table_div5"></div>
<div class="div2" id="columnchart_values" style="width: 640px; height: 300px;"></div>
</div>

<script>
//Erwtima 3 , pop up gia epivevaiwsi otan o admin patisei delete database.
function myFunction() {
  var txt;
  var r = confirm("You are going to delete your database, are you sure?");
  if (r == true) {
    window.location.href="server.php?delete='1'";
    
  } else {
    txt = "You pressed Cancel!";
  }
  
  document.getElementById("demo").innerHTML = txt;
}
</script>
</body>
</html>