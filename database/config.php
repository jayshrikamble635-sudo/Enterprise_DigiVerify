<?php

/*
|--------------------------------------------------------------------------
| Enterprise DigiVerify - Production Database Configuration
|--------------------------------------------------------------------------
| Works on Render using environment variables.
| Also works locally with XAMPP defaults.
|--------------------------------------------------------------------------
*/

error_reporting(0);
ini_set('display_errors', '0');

mysqli_report(MYSQLI_REPORT_OFF);

/*
|--------------------------------------------------------------------------
| Database settings
|--------------------------------------------------------------------------
*/

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int)(getenv('DB_PORT') ?: 3306);
$DB_NAME = getenv('DB_NAME') ?: 'digiverify';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: '';

/*
|--------------------------------------------------------------------------
| Create MySQL connection
|--------------------------------------------------------------------------
*/

$conn = mysqli_init();

if (!$conn) {
    error_log('DigiVerify: mysqli_init() failed.');
    die('ERROR: Database initialization failed.');
}

if (!mysqli_real_connect(
    $conn,
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    $DB_PORT
)) {

    error_log(
        'DigiVerify Database Error: ' .
        mysqli_connect_error()
    );

    die('ERROR: Database connection failed.');
}

/*
|--------------------------------------------------------------------------
| Character set
|--------------------------------------------------------------------------
*/

mysqli_set_charset($conn, 'utf8mb4');

?>
