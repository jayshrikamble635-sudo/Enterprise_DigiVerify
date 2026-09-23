<?php

header('Content-Type: text/plain');

$host = getenv('DB_HOST') ?: 'NOT_SET';
$port = getenv('DB_PORT') ?: 'NOT_SET';
$user = getenv('DB_USER') ?: 'NOT_SET';
$db   = getenv('DB_NAME') ?: 'NOT_SET';
$pass = getenv('DB_PASSWORD') ?: '';

echo "DB_HOST: $host\n";
echo "DB_PORT: $port\n";
echo "DB_USER: $user\n";
echo "DB_NAME: $db\n";
echo "DB_PASSWORD: " . ($pass !== '' ? 'SET' : 'NOT_SET') . "\n\n";

$conn = @mysqli_connect(
    $host,
    $user,
    $pass,
    $db,
    (int)$port
);

if (!$conn) {
    echo "DATABASE: FAILED\n";
    echo "MYSQL ERROR: " . mysqli_connect_error() . "\n";
    exit;
}

echo "DATABASE: CONNECTED\n";

$result = mysqli_query($conn, "SELECT 1");

echo $result ? "QUERY: OK\n" : "QUERY: FAILED\n";

mysqli_close($conn);
