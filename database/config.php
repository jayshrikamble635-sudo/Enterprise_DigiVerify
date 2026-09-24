<?php

error_reporting(0);
ini_set('display_errors', '0');

mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int)(getenv('DB_PORT') ?: 3306);
$DB_NAME = getenv('DB_NAME') ?: 'digiverify';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: '84GsW8L3mHRKuB9lY.YU9.h';

$conn = mysqli_init();

if (!$conn) {
    error_log('DigiVerify: mysqli_init() failed.');
    die('ERROR: Database initialization failed.');
}

/*
 * MariaDB Cloud SSL/TLS
 */
$sslCA = getenv('DB_SSL_CA') ?: '/etc/secrets/globalsignrootca.pem';

if (is_file($sslCA)) {
    mysqli_ssl_set(
        $conn,
        null,
        null,
        $sslCA,
        null,
        null
    );
}

/*
 * Database connection
 */
$connected = false;

if (is_file($sslCA)) {
    $connected = mysqli_real_connect(
        $conn,
        $DB_HOST,
        $DB_USER,
        $DB_PASS,
        $DB_NAME,
        $DB_PORT,
        null,
        MYSQLI_CLIENT_SSL
    );
} else {
    $connected = mysqli_real_connect(
        $conn,
        $DB_HOST,
        $DB_USER,
        $DB_PASS,
        $DB_NAME,
        $DB_PORT
    );
}

if (!$connected) {
    error_log(
        'DigiVerify Database Error: ' .
        mysqli_connect_error()
    );

    die('ERROR: Database connection failed.');
}

mysqli_set_charset($conn, 'utf8mb4');

?>
