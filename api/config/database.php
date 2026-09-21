<?php

// अपने लाइव या क्लाउड डेटाबेस की क्रेडेंशियल्स यहाँ दर्ज करें
$host = "YOUR_LIVE_DATABASE_HOST"; // उदा. ://render.com या clever-cloud Host
$user = "YOUR_LIVE_DATABASE_USER";
$password = "YOUR_LIVE_DATABASE_PASSWORD";
$database = "YOUR_LIVE_DATABASE_NAME";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("Database Connection Failed : " . $conn->connect_error);
}

?>
