<?php

// Clever Cloud लाइव डेटाबेस क्रेडेंशियल्स 
$host = "://clever-cloud.com";
$user = "usmmcxltshqjsde2";
$password = "4yIROXJGxupdTdzB6dZm";
$database = "bnljgn27equjadrmm6w7";

// बिना किसी चेतावनी के सीधे MySQL से कनेक्शन बनाना
$conn = @new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed : " . $conn->connect_error);
}

// यह कनेक्शन को लाइव सर्वर के लिए एकदम पक्का (Strict) कर देगा
$conn->set_charset("utf8mb4");

?>
