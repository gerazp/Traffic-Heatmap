<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
   <title>User registration system</title>
   <link rel="stylesheet" type="text/css" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">                             
</head>
<body>
<div class ="container"></div>
    <div class ="containerhidden">
        <div class="loginbox">
            <h2>Register</h2>
           
    
            <form method="post" action="register.php">
                <?php include('errors.php'); ?>
                

                <div class="textbox">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" placeholder="Enter Username" name="username"required> 
            
                </div>
                <div class="textbox">
                    <i class="fa fa-envelope" aria-hidden="true"></i>

                    <input type="text" placeholder="Enter Email" name="email"required>
            


                </div>
                <div class="textbox">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    <input type="text" placeholder="Enter Password" name="password_1"required>
            
                </div>
                <div class="textbox">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    <input type="text" placeholder="Confirm Password" name="password_2"required>
           
                </div>
                
                <input class="btn" type="submit" name="register" value="Register">
                    
                <p> 
                    Already a member? <a href="login.php"><b><font color="White">Sign in</font></b></a>
                </p>    
            <form> 
        </div>       
    </div>
</body>
</html>