<?php

error_reporting(0);
ini_set('display_errors', '0');
mysqli_report(MYSQLI_REPORT_OFF);

/*
|--------------------------------------------------------------------------
| Enterprise DigiVerify - Server Database Configuration
|--------------------------------------------------------------------------
| Local XAMPP:
|   DB_HOST=127.0.0.1
|
| Render:
|   DB_HOST = Render MySQL internal hostname
|   DB_PORT = 3306
|   DB_NAME = digiverify
|   DB_USER = mysql
|   DB_PASSWORD = your_mysql_password
|--------------------------------------------------------------------------
*/

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int)(getenv('DB_PORT') ?: 3306);
$DB_NAME = getenv('DB_NAME') ?: 'digiverify';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: '';

$conn = mysqli_init();

if (!$conn) {
    error_log('DigiVerify: mysqli_init failed.');
    die('ERROR: Database initialization failed.');
}

mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 10);

if (!mysqli_real_connect(
    $conn,
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    $DB_PORT
)) {
    error_log(
        'DigiVerify DB connection failed: ' .
        mysqli_connect_error()
    );

    die('ERROR: Database connection failed.');
}

mysqli_set_charset($conn, 'utf8mb4');
