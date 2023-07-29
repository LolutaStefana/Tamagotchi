<?php
require_once "configuration.php";

$username = $_POST['username'];
$password= $_POST['password'];


$sql_query = "insert into user(username,password) values('$username','$password')";
global $connection;
$result = mysqli_query($connection, $sql_query);
if ($result) {
    echo "Your account was created";
    header("Location: index.php");
} else {
    echo "Something went wrong.";
}
mysqli_close($conn);