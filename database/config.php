<?php

// 🔥 Clever Cloud MySQL Live Connection (CORRECTED PASSWORD)
$host = "://clever-cloud.com";
$user = "usmmcxltshqjsde2";
$pass = "4yIROXJGxupdTzB6dZm"; // 💡 बिना किसी गलती के सही पासवर्ड
$db   = "bnljgn27equjadrmm6w7";
$port = 3306;

// MySQLi कनेक्शन बनाना
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if(!$conn){
    die("Database Connection Failed : " . mysqli_connect_error());
}

?>
