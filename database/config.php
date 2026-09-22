<?php

// 🔥 Clever Cloud MySQL Live Connection (CORRECTED HOST)
$host = "bnljgn27equjadrmm6w7-mysql.services.clever-cloud.com";
$user = "usmmcxltshqjsde2";
$pass = "4yIROXJGxupdTzB6dZm";
$db   = "bnljgn27equjadrmm6w7";
$port = 3306;

// MySQLi कनेक्शन बनाना (पोर्ट नंबर के साथ)
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if(!$conn){
    die("Database Connection Failed : " . mysqli_connect_error());
}

?>
