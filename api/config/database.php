<?php

// Clever Cloud लाइव डेटाबेस क्रेडेंशियल्स
$host = "://clever-cloud.com";
$user = "bnljgn27equjadrmm6w7";
$password = "यहाँ_अपना_CLEVER_CLOUD_PASSWORD_पेस्ट_करें"; // <-- यहाँ अपना असली पासवर्ड डालें
$database = "bnljgn27equjadrmm6w7";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("Database Connection Failed : " . $conn->connect_error);
}

?>
