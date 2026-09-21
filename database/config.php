<?php

// Clever Cloud लाइव डेटाबेस क्रेडेंशियल्स
$host = "://clever-cloud.com";
$user = "usmmcxltshqjsde2";
$pass = "4yIROXJGxupdTdzB6dZm";
$db   = "bnljgn27equjadrmm6w7";

// MySQLi कनेक्शन बनाना
$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Database Connection Failed : " . mysqli_connect_error());
}

?>
