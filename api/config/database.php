<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "digiverify";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode([
        "status" => false,
        "message" => "Database Connection Failed: " . $conn->connect_error
    ]));
}