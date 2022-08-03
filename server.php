<?php 
session_start();
error_reporting(E_ERROR | E_PARSE);
$username="";
$email = "";
$errors = array();
// eisagwgh sthn database
$db = mysqli_connect('localhost', 'root', '', 'database') or die("could not connect to database");
//REGISTER USERS
//if the button is clicked
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);  
    //elegxos gia kapoio error
    if (empty($username)) {
    array_push($errors, "Username is required");
    }
    if(empty($email)) {
    array_push($errors, "Email is required");
    }   
    if(empty($password_1)) {
    array_push($errors, "Password is required");
    }
    if(empty($password_2)) {
    array_push($errors, "Password is required");
    } 
    if($password_1 != $password_2){
    array_push($errors, "Passwords do not match");
    }
    if(strlen($password_1) < 8){
    array_push($errors, "Password must be at least 8 characters");
    }
    // elenxos kefalaiwn , ari8mwn kai special xarakthrwn
    if(!preg_match('/[A-Z]/', $password_1)){
    // There is one uppercase
        [array_push($errors, "Password must contain at least one uppercase letter")];
    }
    if(!preg_match('/[0-9]/', $password_1)){
    
        [array_push($errors, "Password must contain at least one number")];
    }
    if (!preg_match("/\W/", $password_1)){
        // one or more of the 'special characters' found in password
        [array_push($errors, "Password must contain at least one special character")];
    }
    $sql = "SELECT * FROM user WHERE username= '$username' or email= '$email' ";
    $result=mysqli_query($db, $sql);
    $counts=mysqli_num_rows($result);
    //den epitrepoume register me idio email h username
    if ($counts>0){
        [array_push($errors, "This username or email has been taken.Do you already have an account?")];
    }
    if(count($errors) == 0 ){
        $method = 'aes128'; // me8odos gia to encrypt
        $id= openssl_encrypt ($email, $method, $password_1); //kanoume encrypt me to email kai  me kleidi to password tou xrhsth
        $password = md5($password_1); //kanoume hash ston kwdiko kai ta apo8hkeuoume stin vasi dedomenwn
        $insertion = "INSERT INTO user (id,username, email, password)  
                        VALUES ('$id','$username', '$email', '$password')";
        mysqli_query($db, $insertion);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Welcome to the club";
        //mas xrisimeuei argotera gia to cover tou xarti (an dhladh o xrhsths 8elei na kanei cover)
        $_SESSION['boolean'] = false;

        header('location: indexuser.php');
          
    }
    
}
    
//LOGIN USER
if(isset($_POST['login'])) {
 
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password_1']);
    if(empty($username)) {
        array_push($errors, "Username is required");
    }
    if(empty($password)){
        array_push($errors, "Password is required");
    }
        
    if(count($errors) === 0 ) {
        if($username==="myadmin" AND $password=== "myadmin"){
            header('location: indexadmin.php');
        }
        
        $password= md5($password);
        $query= "SELECT * FROM user WHERE username='$username' AND password = '$password'";
        $result = mysqli_query($db, $query);
             
        if(mysqli_num_rows($result) >= 1) {
                
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "KALOSHRTHES";
            $_SESSION['boolean'] = false;
           
           
            header('location: indexuser.php');
        

         }else{
        array_push($errors, "Wrong username/password. Try again");
       
        }  
    } 
        mysqli_free_result($result);
    }

//max kai min suntetagmenes gia apostasi peripou 12,5 xiliometra gurw apo tin patra
$maxlatitute =38.336298; 
$minlatitute =38.156117; 
$maxlongitute =21.843239; 
$minlongitute =21.663059;
//cover xarth
if (isset($_POST['remove'])) {

    $_SESSION['boolean'] = true;
    //pernoume tis times apo to covermap gia tis opoies o xrhsths 8elei na krupsei.
    $panw= $_POST['panw'];
    $katw= $_POST['katw'];
    //tis xwrizoume se lat kai lng
    $panwarray=explode(',',$panw);
    $panwlat= $panwarray[0];
    $panwlng= $panwarray[1];
    
    $katwarray=explode(',',$katw);
    $katwlat= $katwarray[0];
    $katwlng= $katwarray[1];

    $newusername = $_SESSION['username'];
    $sql = "SELECT * FROM user WHERE username= '$newusername'";
    $result=mysqli_query($db, $sql);
    $rows1=mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    //pername ta stoixeia pou o xrhsths 8elei na kanei cover stin vasi dedomenwn
    $insertion = "INSERT INTO coveredarea(panwlat, katwlat, panwlng, katwlng, id)  
    VALUES ('$panwlat', '$katwlat', '$panwlng', '$katwlng','".$rows1['id']."') "; 
    mysqli_query($db, $insertion);
    echo "<script type='text/javascript'> areaSelect.remove();</script>";

}
//UPLOAD
if (isset($_POST['sumbit'])){
   
    $coverbool=$_SESSION['boolean'];
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = "json";
    
    
    if ( $fileActualExt ===  $allowed ){
        if ($fileError === 0){
            $newusername = $_SESSION['username'];
            //vriskw to id tou sugkekrimenou user
            $sql = "SELECT * FROM user WHERE username= '$newusername'";
            $result=mysqli_query($db, $sql);
            $rows=mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            $timenowsecs = time();
            
            
            $fileNewName = uniqid('', true).".".$fileActualExt;
            $fileDestination = 'uploads/'.$fileNewName;
            move_uploaded_file($fileTmpName, $fileDestination);       
                 
            //pername stin database mas to onoma tou arxeiou ston antistoixo user     
            $newusername = $_SESSION['username'];
            $databasefilename = "INSERT INTO userfiles(from_id,filename,uploadtimestamp) VALUES ('".$rows['id']."','".$fileNewName."','".$timenowsecs."')";
            mysqli_query($db, $databasefilename);    
            $str = file_get_contents($fileDestination);
            $new = json_decode($str, TRUE);
            $count1 = 0;
            //ftiaxnoume mia metavliti boolean gia na doume an exei perasei swsta stoixeia h uparxei kapoio error
            $mp= false;
           //vlepoume ean o xristis exei kanei cover kai energoume analoga
            if ($coverbool ===true) {
                $sql = "SELECT * FROM coveredarea WHERE id= '".$rows['id']."' ";
                $result=mysqli_query($db, $sql);
                $rows=mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                $panwlat= $rows['panwlat'] ;
                $katwlat= $rows['katwlat'];
                $katwlng= $rows['katwlng'];
                $panwlng= $rows['panwlng'];
            }
            foreach($new['locations'] as $key){
                // elenxos gia latitude kai longitute
                $temp1= $new['locations'][$count1]['latitudeE7'] / 10000000;
                $temp2=$new['locations'][$count1]['longitudeE7'] / 10000000;
                //dhmiourgoume ena column month pou mas boh8aei gia ta epomena erwtimata
                $timeSeconds = $new['locations'][$count1]['timestampMs'] / 1000;
                $time = round($timeSeconds);
                $month = date('n', $time);
                $year = date('Y',$time);
                $order_date = date('Y-m-d',$time);
                $hour= date('G',$time);
                $day=date('l',$time);
                //elegxoume an exei kanei cover kapoies perioxes
                
               
               //fortwnoume to arxeio kai pername tis plhrofories pou mas endiaferoun stin database
                if($coverbool ===true){
                    if (!( $temp1 >= $panwlat and $temp1<= $katwlat and $temp2 >= $panwlng and $temp2 <= $katwlng )){
                    if($temp1 <= $maxlatitute and $temp1 >= $minlatitute ){

                        if($temp2 <= $maxlongitute and $temp2 >= $minlongitute ){
                            $sql = "INSERT INTO userjson(timestampMs,latitudeE7,longitudeE7,accuracy,activity,month,year,id,order_date,hour,day) VALUES ('".$new['locations'][$count1]['timestampMs']."', '".$new['locations'][$count1]['latitudeE7']."', '".$new['locations'][$count1]['longitudeE7']."', '".$new['locations'][$count1]['accuracy']."', NULL , '".$month."', '".$year."','".$rows['id']."', '".$order_date."','".$hour."','".$day."')";
                                if (isset ($new['locations'][$count1]['activity'])){
                                    $count2 = 0;
                                    if (isset ($new['locations'][$count1]['activity'][$count2]['activity'])){
                                        if(isset($new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type'])){
                                            $tab = $new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type'] ;
                                            $sql = "INSERT INTO userjson(timestampMs,latitudeE7,longitudeE7,accuracy,activity,month,year,id,order_date,hour,day) VALUES ('".$new['locations'][$count1]['timestampMs']."', '".$new['locations'][$count1]['latitudeE7']."', '".$new['locations'][$count1]['longitudeE7']."', '".$new['locations'][$count1]['accuracy']."', '".$new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type']."', '".$month."','".$year."','".$rows['id']."', '".$order_date."','".$hour."','".$day."')";
                                        }
                                    }
                                }
                            mysqli_query($db, $sql);
                            $mp=true;
                        }
                    }
                    }
                }else{
                if($temp1 <= $maxlatitute and $temp1 >= $minlatitute ){

                    if($temp2 <= $maxlongitute and $temp2 >= $minlongitute){
                        $sql = "INSERT INTO userjson(timestampMs,latitudeE7,longitudeE7,accuracy,activity,month,year,id,order_date,hour,day) VALUES ('".$new['locations'][$count1]['timestampMs']."', '".$new['locations'][$count1]['latitudeE7']."', '".$new['locations'][$count1]['longitudeE7']."', '".$new['locations'][$count1]['accuracy']."', NULL , '".$month."', '".$year."','".$rows['id']."', '".$order_date."','".$hour."','".$day."')";
                            if (isset ($new['locations'][$count1]['activity'])){
                                $count2 = 0;
                                if (isset ($new['locations'][$count1]['activity'][$count2]['activity'])){
                                    if(isset($new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type'])){
                                        $tab = $new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type'] ;
                                        $sql = "INSERT INTO userjson(timestampMs,latitudeE7,longitudeE7,accuracy,activity,month,year,id,order_date,hour,day) VALUES ('".$new['locations'][$count1]['timestampMs']."', '".$new['locations'][$count1]['latitudeE7']."', '".$new['locations'][$count1]['longitudeE7']."', '".$new['locations'][$count1]['accuracy']."', '".$new['locations'][$count1]['activity'][$count2]['activity'][$count2]['type']."', '".$month."','".$year."','".$rows['id']."', '".$order_date."','".$hour."','".$day."')";
                                    }
                                }
                            }
                        mysqli_query($db, $sql);
                        $mp=true;
                    }
                }
                }
                $count1 = $count1 +1;
            }
            
            if ($mp){
                header('location: indexuser.php');
    
            }else{
                echo "There are no eligible co-ordinates.";
            }
                
        }else{
            echo "There was an error with your upload";
        }
    
    
    
    }else{
        echo "You cannot upload file of this type";
    }
    
    
}
//ERWTHMA 3.a Xrhsth
if(isset($_POST["from_date"], $_POST["to_date"])) {
    
    $db = mysqli_connect('localhost', 'root', '', 'database');
    
   
    $array = array("WALKING", "IN_VEHICLE","ON_BICYCLE","ON_FOOT","RUNNING","STILL","TILTING","UNKNOWN");
    $newusername = $_SESSION['username'];
    $sql = "SELECT * FROM user WHERE username= '$newusername'";
    $result=mysqli_query($db, $sql);
    $rows1=mysqli_fetch_assoc($result);
    mysqli_free_result($result);
        
        //pernoume tis eggrafes anamesa stis hmeromhnies pou evale o xristis
        for ($i = 0; $i < 9; $i++) {
            $sql1= "SELECT COUNT(*) FROM userjson WHERE  order_date >= '".$_POST["from_date"]."' AND  order_date <= '".$_POST["to_date"]."' AND id= '".$rows1['id']."' " ;
            $result = mysqli_query($db, $sql1);
             $rows=mysqli_fetch_array($result);
             mysqli_free_result($result);
             $countactivity=$rows[0];
        }
        for ($i = 0; $i <8; $i++) {
            $sql = "SELECT COUNT(activity)  FROM userjson WHERE  activity='$array[$i]'  AND order_date >= '".$_POST["from_date"]."' AND  order_date <= '".$_POST["to_date"]."' AND id= '".$rows1['id']."' " ;
                
                $result = mysqli_query($db, $sql);
                $rows=mysqli_fetch_array($result);
                mysqli_free_result($result);
                $count[$i]=$rows[0];
               
                }
$counter =$count[0]+$count[1]+$count[2]+$count[3]+$count[4]+$count[5]+$count[6]+$count[7];
$countnull=$countactivity-$counter;
$counter=$counter+$countnull;
if($counter != 0){   
   
    for ($i = 0; $i < 8; $i++){
                 $pr=   ($count[$i]/$counter)*100;
                $prest[$i]="$pr"."%";
               
            }
            $en=json_encode($prest);
            //pername tis antistoixes times stin javascript (mapuser) gia na emfanistei to pinakaki
            echo "<script type='text/javascript'> var jprest = '$en'  ;</script>";
            $en=json_encode($array);
            echo "<script type='text/javascript'> var jarray = '$en'  ;</script>";
            $prestnull=($countnull/$counter)*100;
            $en=json_encode($prestnull);
            echo "<script type='text/javascript'> var jprestnull = '$en'  ;</script>";
           
          
         
       
    //ERWTHMA 3 b,c
    for ($i = 0; $i < 8; $i++){
        //psaxnoume ta stoixeia pou emfanizontai most frequent
        $sql="SELECT hour, COUNT(hour) AS MOST_FREQUENT
        FROM userjson
        WHERE activity IN (SELECT activity FROM userjson WHERE activity='$array[$i]' AND order_date >= '".$_POST["from_date"]."' AND  order_date <= '".$_POST["to_date"]."' AND id= '".$rows1['id']."')
        GROUP BY hour
        ORDER BY COUNT(hour) DESC ";
        
        $result = mysqli_query($db, $sql);
        $rows=mysqli_fetch_array($result);
        mysqli_free_result($result);
        $con[$i]=$rows[0];
        $sql="SELECT day, COUNT(day) AS MOST_FREQUENT
        FROM userjson
        WHERE activity IN (SELECT activity FROM userjson WHERE activity='$array[$i]' AND order_date >= '".$_POST["from_date"]."' AND  order_date <= '".$_POST["to_date"]."' AND id= '".$rows1['id']."')
        GROUP BY day
        ORDER BY COUNT(day) DESC ";
        
        $result = mysqli_query($db, $sql);
        $rows=mysqli_fetch_array($result);
        mysqli_free_result($result);
        $con1[$i]=$rows[0];
        }
        $en=json_encode($con);
        echo "<script type='text/javascript'> var jhour = '$en'  ;</script>";
        $en=json_encode($con1);
        echo "<script type='text/javascript'> var jday = '$en'  ;</script>";
        
    }else{
        echo "<div class='errortext'>"."<center>"."<b>"."There are no results"."</b>"."</center>"."</div>";
      }

      //heatmap 
      $sql= "SELECT latitudeE7, longitudeE7 FROM userjson WHERE  id= '".$rows1['id']."' ";
 $result = mysqli_query($db, $sql);
 $i=0;
 
 while ($rows = mysqli_fetch_array($result)){
 
 $lat[$i]=$rows[0]/10000000;
 $lon[$i]=$rows[1]/10000000;
 
 
 $i=$i+1;
 }
 mysqli_free_result($result);
 $enlat=json_encode($lat);
 $enlon=json_encode($lon);
 echo "<script type='text/javascript'>var jlat = '$enlat ';</script>";
 echo "<script type='text/javascript'>var jlon = '$enlon ';</script>";
 
 
    }

    //ADMIN 2
    if(isset($_POST["Year"], $_POST["Month"], $_POST["Day"], $_POST["Hour"], $_POST["Activity"])) {
        //an o xristis patisei select all tote ftiaxnoume kateu8eian to array
        if(in_array("ALL",$_POST["Year"])){
            $stingyear='2000 2001 2002 2003 2004 2005 2006 2007 2008 2009 2010 2011 2012 2013 2014 2015 2016 2017 2018 2019 2020';
        }else{ //alliws
            $type=$_POST["Year"];
        
            $stingyear=implode(' ',$type);  //me to implode ftiaxnoume ena string me ta orismata pou edwse o xristis xwrismena me keno
        }
            $yeararray=explode(' ',$stingyear); //meta metatrepoume to apo panw string se ena array
            $likes1 = array();
            foreach($yeararray as $oneyear) {
               $likes1[] = "year LIKE '%$oneyear%'"; //ftiaxnoume enan pinaka efoson den kseroume posa orismata 8a mas dwsei o xrhsths meta auto to metatrepoume se string psaxnoume stin vasi dedomenwn mas
            }
        if(in_array("ALL",$_POST["Month"])){
            $stingmonth='1 2 3 4 5 6 7 8 9 10 11 12';
        }else{
           $type=$_POST["Month"];
           
            $stingmonth=implode(' ',$type); 

        }
            $montharray=explode(' ',$stingmonth);
            $likes2 = array();
            foreach($montharray as $onemonth) {
               
               $likes2[] = "month ='$onemonth'";
            }


        if(in_array("ALL",$_POST["Day"])){

          $stingday='Monday Tuesday Wednesday Thursday Friday Saturday Sunday';
        }else{  
          $type=$_POST["Day"];
           
          $stingday=implode(' ',$type); 
        }
          $dayarray=explode(' ',$stingday);
          $likes3 = array();
          foreach($dayarray as $oneday) {
            
             $likes3[] = "day LIKE '%$oneday%'";
          }


        if(in_array("ALL",$_POST["Hour"])){
            $stinghour='0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23';
        }else{
         $type=$_POST["Hour"];
           
         $stinghour=implode(' ',$type); 
        }
         $hourarray=explode(' ',$stinghour);
         $likes4 = array();
         foreach($hourarray as $onehour) {
            $likes4[] = "hour ='$onehour'";
         }


         if(in_array("ALL",$_POST["Activity"])){
            $stingactivity='WALKING IN_VEHICLE ON_BICYCLE ON_FOOT RUNNING STILL TILTING UNKNOWN';
            
        }else{
        $type=$_POST["Activity"];
           
        $stingactivity=implode(' ',$type);
      
        } 
        $activityarray=explode(' ',$stingactivity);
        $likes5 = array();
        foreach($activityarray as $oneactivity) {
           $likes5[] = "activity LIKE '%$oneactivity%'";
        }


        $merge = array();
         $merge[0]="(".implode(' or ', $likes1).")"; //metatrepoume ton pinaka se ena dynamic string kai vazoume stin arxi kai sto telos tou string paren8eseis gia na ginei dekto sto SQL query
         $merge[1]="(".implode(' or ', $likes2).")";
         $merge[2]="(".implode(' or ', $likes3).")";
         $merge[3]="(".implode(' or ', $likes4).")";
         $merge[4]="(".implode(' or ', $likes5).")";
        
        //me auti tin metavliti ka8orizoume an uparxoun eggrafes gia tis epiloges pou evale o xrhsths
        $tf=false;
        $j=0;

        $op="". implode(' and ', $merge);
    
        $sql= "SELECT latitudeE7, longitudeE7 FROM userjson WHERE " . implode(' and ', $merge); //psaxnoume stis vasi dedomenwn me to dynamic string pou dhmiourgisame
        $result=mysqli_query($db, $sql);
        
       // to kanoume session epeidh to xreiazomaste argotera gia to export
        $_SESSION['merge'] = $merge;
        //pername tis aparaitites times gia na emfanistei to heatmap
        while ($rows=mysqli_fetch_array($result)){
        
        $lat[$j]=$rows[0]/10000000;
        $lon[$j]=$rows[1]/10000000;
        //gia na mpainei mono tin prwti fora
        if ($tf===false){
        $tf=true;
        }
        
        $j=$j+1;
       
        }
       
        mysqli_free_result($result);

    
        if ($tf){
        $enlat=json_encode($lat);
        $enlon=json_encode($lon);
            echo "<script type='text/javascript'>var jlat = '$enlat ';</script>";
            echo "<script type='text/javascript'>var jlon = '$enlon ';</script>";
        }else{
            echo "<div class='errortext'>"."<center>"."<b>"."There are no results"."</b>"."</center>"."</div>";
        }




    }
        //EXPORT
//EXPORT


    
if(isset($_GET['export'])) {
    $newmerge = $_SESSION['merge'];
$query = "SELECT * FROM userjson WHERE " . implode(' and ', $newmerge);
$result = mysqli_query($db,$query);
$user_arr = array();
while($row = mysqli_fetch_array($result)){
 $year = $row['year'];
 $month = $row['Month'];
 $day = $row['day'];
 $hour = $row['hour'];
 $activity = $row['activity'];
 $userid= $row['id'];
 $user_arr[] = array($year,$month,$day,$hour,$activity,$userid);
}
$serialize_user_arr = serialize($user_arr);//Generates a storable representation of a value,this is useful for storing or passing PHP values around without losing their type and structure.


    $filename = 'data.csv';
    $export_data = unserialize($serialize_user_arr);//takes a single serialized variable and converts it back into a PHP value.

    // file creation
    $file = fopen($filename,"w");
   
    foreach ($export_data as $line){
     fputcsv($file,$line);
    }
    
    fclose($file);
    
    // download
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Type: application/csv; "); 
    
    readfile($filename);
    
}






    
//delete database

    
if(isset($_GET['delete'])) {
    
    $sql="DELETE FROM user";
    mysqli_query($db, $sql);
    $sql="DELETE FROM coveredarea";
    mysqli_query($db, $sql);
    $sql="DELETE FROM userfiles";
    mysqli_query($db, $sql);
    $sql="DELETE FROM userjson";
    mysqli_query($db, $sql);
    header('location: indexadmin.php');
    }
    
    
    
    
    

    
    mysqli_close($db);  
    
    

 ?>