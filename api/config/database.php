<?php

// Render Dashboard से सीधे लाइव डेटाबेस क्रेडेंशियल्स उठाना
$host = getenv('DB_HOST') ?: "://clever-cloud.com";
$user = getenv('DB_USER') ?: "usmmcxltshqjsde2";
$password = getenv('DB_PASS') ?: "4yIROXJGxupdTdzB6dZm";
$database = getenv('DB_NAME') ?: "bnljgn27equjadrmm6w7";

$conn = @new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed : " . $conn->connect_error);
}

?>
