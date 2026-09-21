<?php

// Clever Cloud लाइव डेटाबेस क्रेडेंशियल्स
$host = "://clever-cloud.com";
$user = "usmmcxltshqjsde2";
$password = "4yIROXJGxupdTdzB6dZm";
$database = "bnljgn27equjadrmm6w7";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("Database Connection Failed : " . $conn->connect_error);
}

?>
