<?php
    ///take the name and pass it to the welcome page
    session_start();

    include('configuration.php');
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $_SESSION['username'] = $username;
        
       

        $sql = "select * from user where username = '$username' and password = '$password' ";  
        $result = mysqli_query($connection, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
        
        if($count == 1){  
           
            
            header("Location: backend.php");
        }  
        else{  
            echo  '<script>
                        window.location.href = "index.php";
                        alert("Login failed. Username or password is incorrect.")
                    </script>';
        }     
    }
    ?>