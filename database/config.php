<?php

error_reporting(0);
ini_set('display_errors', '0');

mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = getenv('DB_HOST') ?: '';
$DB_PORT = (int)(getenv('DB_PORT') ?: 4066);
$DB_NAME = getenv('DB_NAME') ?: 'digiverify';
$DB_USER = getenv('DB_USER') ?: '';
$DB_PASS = getenv('DB_PASSWORD') ?: '';

$conn = mysqli_init();

if (!$conn) {
    die('ERROR: Database initialization failed.');
}

/*
 * MariaDB Cloud SSL connection
 * Temporary diagnostic mode:
 * encrypted SSL connection, without certificate verification.
 */
$connected = mysqli_real_connect(
    $conn,
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    $DB_PORT,
    null,
    MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
);

if (!$connected) {
    die(
        'DB ERROR: ' .
        mysqli_connect_errno() .
        ' - ' .
        mysqli_connect_error()
    );
}

mysqli_set_charset($conn, 'utf8mb4');

?>
