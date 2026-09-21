<?php

// ट्रिम फंक्शन के साथ लाइव क्रेडेंशियल्स ताकि कोई भी छुपा हुआ स्पेस या कैरेक्टर एरर न दे
$host = trim("://clever-cloud.com");
$user = trim("usmmcxltshqjsde2");
$password = trim("4yIROXJGxupdTdzB6dZm");
$database = trim("bnljgn27equjadrmm6w7");

// बिना किसी चेतावनी के सीधे MySQL से कनेक्शन बनाना
$conn = @new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed : " . $conn->connect_error);
}

// यह कनेक्शन को लाइव सर्वर के लिए एकदम पक्का (Strict) कर देगा
$conn->set_charset("utf8mb4");

?>
