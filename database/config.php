<?php

// Changed "localhost" to "127.0.0.1" for instant query routing and to fix XAMPP lag
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "digiverify";


$conn = mysqli_connect($host,$user,$pass,$db);


if(!$conn){

    die("Database Connection Failed : " . mysqli_connect_error());

}


// Set character encoding
mysqli_set_charset($conn,"utf8");

?>
