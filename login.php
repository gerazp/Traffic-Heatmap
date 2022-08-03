<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
   <title>User login system</title>
   <link rel="stylesheet" href="style.css">  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">                     
</head>
<body>

    <div class ="container"></div>
    <div class ="containerhidden">
        <div class="loginbox">
            <h2>Login</h2>
        
            <form method="post" action="login.php">
                <?php include('errors.php'); ?>
            <div class="textbox">
          
            <i class="fa fa-user" aria-hidden="true"></i>
                <input type="text" placeholder="Enter Username" name="username"required> 
                
            </div>
            <div class="textbox">
                <i class="fa fa-key" aria-hidden="true"></i>

                <input type="password" placeholder="Enter Password" name="password_1"required>
            </div>
         
                <input class="btn" type="submit" name="login" value="Login">
           
            <p> 
                New here? <a href="register.php"><b><font size="4" color="white">Register now!</font></b></a>
            </p>    
            </form>        
        </div>
</div>
    
</body>
</html>