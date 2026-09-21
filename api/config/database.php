<?php

// Clever Cloud लाइव डेटाबेस क्रेडेंशियल्स
$host = "://clever-cloud.com";
$user = "bnljgn27equjadrmm6w7";
$password = "अपना_पासवर्ड_यहाँ_पेस्ट_करें"; // <-- उद्धरण चिह्नों (' ') के बीच में अपना असली पासवर्ड पेस्ट करें
$database = "bnljgn27equjadrmm6w7";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("Database Connection Failed : " . $conn->connect_error);
}

?>
