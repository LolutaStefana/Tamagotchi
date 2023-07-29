<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "Tamagotchi";  
    
    $connection = new mysqli($servername, $username, $password, $db_name);
    if($connection->connect_error){
        die("Connection failed".mysqli_connect_error());
    }
   
    
    ?>