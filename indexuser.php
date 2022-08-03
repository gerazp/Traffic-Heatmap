<?php include('server.php'); ?>


<?php

error_reporting(E_ERROR | E_PARSE);
$db= mysqli_connect("localhost", "root", "", "database");
//pernoume to timestamp otan hr8e to request, gia na emfanisoume swsta tous teleutaious 12 mines
$timenowsec = time();
$monthwewant=intval(date('m', $timenowsec)); 
$year = intval(date('Y', $timenowsec));
$newusername = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username= '$newusername'";
$result=mysqli_query($db, $sql);
$rows1=mysqli_fetch_assoc($result);
mysqli_free_result($result);
//Erwtima 2.A Απεικόνιση στοιχείων χρήστη
for ($i = 0; $i < 12; $i++) {
    if ($monthwewant===0){
        $monthwewant=12;
        $year = $year-1;
    }
   
    $sql= "SELECT COUNT(*) FROM userjson WHERE (month='$monthwewant' AND year='$year') AND (activity='WALKING' OR activity='ON_FOOT' OR activity='RUNNING' OR activity='ON_BICYCLE') AND id= '".$rows1['id']."'";
    $result = mysqli_query($db, $sql);
    $rows=mysqli_fetch_array($result);
   
    $ecomhna[$i] = $rows[0];
    $sql= "SELECT COUNT(*) FROM userjson WHERE (month='$monthwewant' AND year='$year') AND (activity='IN_VEHICLE') AND id= '".$rows1['id']."'" ;
    $result = mysqli_query($db, $sql);
    $rows=mysqli_fetch_array($result);
    mysqli_free_result($result);

    $noecomhna[$i] = $rows[0];
    
    if ($noecomhna[$i]+$ecomhna[$i] == 0){
    $ecopercentage[$i]= 0;
    $necopercentage[$i]= 0;
    }else{
    $ecopercentage[$i]= ($ecomhna[$i]*100)/($noecomhna[$i]+$ecomhna[$i]);
    $necopercentage[$i]= 100 -  $ecopercentage[$i];
    }
    $monthName[$i] = date('F', mktime(0, 0, 0, $monthwewant, 10)); 
    $monthwewant= $monthwewant-1;
}
$sql = "UPDATE  user SET eco_score = '$ecopercentage[0]' WHERE id= '".$rows1['id']."'";
mysqli_query($db, $sql);
$sql="SELECT * FROM user ORDER BY eco_score DESC ";
$result=mysqli_query($db, $sql);


$i=0;
//vazoume mia metavliti boolean gia na doume an o trexon xristis anhkei stin top 3ada h oxi!!
$bool=false;
while ($row = mysqli_fetch_assoc($result) AND $i<3) {

if($row['id']=== $rows1['id']){
  $bool=true;
}
//pairnoume to onoma tou xrhsth kai emfanizoume mono ta prwta 3 grammata
$namearray=explode(' ',$row['username']);
//Mesw tis if elegxoume an o idios o xrhsths einai sthn triada (wste na emfanistei olokliro to onoma tou)
if ($newusername==$row['username']){
  $best[$i]=$row['username'];
  $bestscore[$i]=$row['eco_score'];
  $i=$i+1;
}else{
$best[$i]=$namearray[0][0].$namearray[0][1].$namearray[0][2]."."." ".$namearray[1][0].$namearray[1][1].$namearray[1][2].".";
$bestscore[$i]=$row['eco_score'];
$i=$i+1;
}
}
mysqli_free_result($result);

$temp=1;
//kanoume order stin vasi dedomenwn gia na paroume tous top 3.
$sql="SELECT * FROM user ORDER BY eco_score DESC ";
$result=mysqli_query($db, $sql);
while($row = mysqli_fetch_assoc($result)){
  
  if($row['id']=== $rows1['id']){
    $tetartos=$temp;
  }
  $temp=$temp+1;
}
mysqli_free_result($result);
//Erwtima 2.b
//Vriskoume thn prwth kai thn teleutaia eggrafh tou kathe xrhsth
$sql = "SELECT MIN(timestampMs) FROM userjson WHERE id= '".$rows1['id']."'" ;
$result = mysqli_query($db, $sql);
$rows=mysqli_fetch_array($result);
mysqli_free_result($result);

$mintimestampMs = $rows[0]/1000;
$mindate=date('l jS \of F Y',$mintimestampMs);
$sql = "SELECT MAX(timestampMs) FROM userjson WHERE id= '".$rows1['id']."'" ;
$result = mysqli_query($db, $sql);
$rows=mysqli_fetch_array($result);

mysqli_free_result($result);

$maxtimestampMs = $rows[0]/1000;
$maxdate=date('l jS \of F Y',$maxtimestampMs);
if (($mintimestampMs=== 0) AND ($maxtimestampMs===0)){
  $maxdate= "No records available";
  $mindate= "-";
}
//Vriskoume to prwto kai to teleutaio upload tou kathe xrhsth
$sql = "SELECT MAX(uploadtimestamp) FROM userfiles WHERE from_id= '".$rows1['id']."'" ;
$result = mysqli_query($db, $sql);
$rows=mysqli_fetch_array($result);
mysqli_free_result($result);

$lastupload = $rows[0];
if ($lastupload===NULL){
  $lastuploaddate= "No uploads yet";
}else{
$lastuploaddate = date('l jS \of F Y',$lastupload);
}

mysqli_close($db);
?>
<!DOCTYPE html>
<html> 
<head>
<style>
    body, html { margin:0; padding:0; background-image: url("images/bigdata.png");}
    </style>
    <title>Home page</title>
    <link rel="stylesheet" href="newstyle.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
    
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Months', 'Ecological score', 'Not Ecological score'],
          ["<?php echo $monthName[0]; ?>",<?php echo $ecopercentage[0]/100; ?>,<?php echo $necopercentage[0]/100; ?>],
          ["<?php echo $monthName[1]; ?>",<?php echo $ecopercentage[1]/100; ?>,<?php echo $necopercentage[1]/100; ?>],
          ["<?php echo $monthName[2]; ?>",<?php echo $ecopercentage[2]/100; ?>,<?php echo $necopercentage[2]/100; ?>],
          ["<?php echo $monthName[3]; ?>",<?php echo $ecopercentage[3]/100; ?>,<?php echo $necopercentage[3]/100; ?>],
          ["<?php echo $monthName[4]; ?>",<?php echo $ecopercentage[4]/100; ?>,<?php echo $necopercentage[4]/100; ?>],
          ["<?php echo $monthName[5]; ?>",<?php echo $ecopercentage[5]/100; ?>,<?php echo $necopercentage[5]/100; ?>],
          ["<?php echo $monthName[6]; ?>",<?php echo $ecopercentage[6]/100; ?>,<?php echo $necopercentage[6]/100; ?>],
          ["<?php echo $monthName[7]; ?>",<?php echo $ecopercentage[7]/100; ?>,<?php echo $necopercentage[7]/100; ?>],
          ["<?php echo $monthName[8]; ?>",<?php echo $ecopercentage[8]/100; ?>,<?php echo $necopercentage[8]/100; ?>],
          ["<?php echo $monthName[9]; ?>",<?php echo $ecopercentage[9]/100; ?>,<?php echo $necopercentage[9]/100; ?>],
          ["<?php echo $monthName[10]; ?>",<?php echo $ecopercentage[10]/100; ?>,<?php echo $necopercentage[10]/100; ?>],
          ["<?php echo $monthName[11]; ?>",<?php echo $ecopercentage[11]/100; ?>,<?php echo $necopercentage[11]/100; ?>],
          
        ]);
        var options = {
          
          colors: ['#DA70D6', '#C71585'],
          backgroundColor: 'transparent',
          hAxis: {
                  title: 'Month',
                  textStyle: {
                     color: 'White',
                     fontSize: 14,
                     fontName: 'Arial',
                     bold: true,
                     italic: true
                  },
                  
                  titleTextStyle: {
                     color: 'White',
                     fontSize: 16,
                     fontName: 'Arial',
                     bold: false,
                     italic: true
                  }
               },
               
               vAxis: {
                
                format: 'percent',
                  title: 'Percentage',
                  textStyle: {
                     color: 'White',
                     fontSize: 24,
                     bold: true
                  },
                  titleTextStyle: {
                     color: 'White',
                     fontSize: 24,
                     bold: true
                  }
                  
               },
        };
        var chart = new google.charts.Bar(document.getElementById('columnchart_material',));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
     
</head>


<body>


<div class="graph">
<p style="color:White;"> > The first register that was found was on: <b><?php echo $mindate; ?></b> and the last one on: <b><?php echo $maxdate; ?></b>  </p>
<p style="color:White;"> > The last upload was on: <b><?php echo $lastuploaddate; ?></b></p>
<p style="color:White;"> > The top 3 ecologists are: <b><?php echo $best[0]; ?> ,<?php echo $best[1]; ?>,<?php echo $best[2]; ?><?php if($bool === false){echo "<br>"."while you are ranked as number ".$tetartos." in our database"."</br>";} ?> </b> </p> 

<div id="columnchart_material" style="width: 1300px; height: 620px;"></div>

<h1><center>LEADERBOARD</center></h1>

<center><table style="width:80%; height: 180px;" bgcolor="#DA70D6">
  <tr>
    <th>RANK</th>
    <th>USERNAME</th> 
    <th>ECO_SCORE</th>
  </tr>
  <tr>
    <td><center>1</center></td>
    <td><center><?php echo $best[0]; ?> </center></td>
    <td><center><?php echo $bestscore[0]; ?></center></td>
  </tr>
  <tr>
    <td><center>2</center></td>
    <td><center><?php echo $best[1]; ?></center></td>
    <td><center><?php echo $bestscore[1]; ?></center></td>
  </tr>
  <tr>
    <td><center>3</center></td>
    <td><center><?php echo $best[2]; ?></center></td>
    <td><center><?php echo $bestscore[2]; ?></center></td>
  </tr>
  
  <?php if($bool=== false) : ?>
    
    <tr>
    <td><center><?php echo $tetartos; ?></center></td>
    <td><center><?php echo $newusername; ?></center></td>
    <td><center><?php echo $ecopercentage[0]; ?></center></td>
  </tr>
  <?php endif; ?>
   
  
</table></center>


</div>
<div class="bg-text">
    <h1>City Analyser</h1>
    <?php 
    if (isset($_SESSION["username"])): ?>
        <p>Welcome <strong><?php echo $_SESSION['username']; ?> </strong>,
        would you like to upload your location history file?<a href="covermap.php"><b><font size="4" color="white"><br>You can cover some areas!</font></b></a>
    </p>

    <?php endif ?>

  
    
    
    <form action="server.php" method="POST" enctype="multipart/form-data">
        <input  type="file" name="file">
        <button type="sumbit" name="sumbit">UPLOAD</button>
    </form>
    </div>
    <button class="analize" onclick="window.location.href='mapuser.php'">Data Analysis</button>
    <button class="logout" onclick="window.location.href='logout.php'">Logout</button>
    
    
    
    
    




    
    </body>
</html>