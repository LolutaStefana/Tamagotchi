<?php 
    include("configuration.php");
    include("login.php")
    ?>
    
<html>
    

    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
body {
  background-image: url('sky.jpg');
}
</style>
    </head>
    <body>
    </div>
       
        
        <div id="form">
            <h1>Tamagotchi Game ♡</h1>
            <form name="form" action="login.php" onsubmit="return isvalid()" method="POST">
           

                <label>UserName: </label>
                <input type="text" id="username" name="username"></br></br>
                <label>Password: </label>
                <input type="text" id="password" name="password"></br></br>
                <input type="submit" id="btn" value="Login" name = "submit"/>
                <p>Don't have an account? <a href="signin.html">Sign up now</a>.</p>
              
            </form>
        
        <script>
            function isvalid(){
                var username = document.form.username.value;
                
                if(username.length==""){
                    alert(" UserName field is empty!!!");
                    return false;
                }
               
                
            }
        </script>
    </body>
</html>