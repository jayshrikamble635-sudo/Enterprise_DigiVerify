<?php

error_reporting(0);
ini_set('display_errors', '0');

mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int)(getenv('DB_PORT') ?: 3306);
$DB_NAME = getenv('DB_NAME') ?: 'digiverify';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: '';

$conn = mysqli_init();

if (!$conn) {
    die('ERROR: Database initialization failed.');
}

/*
=========================================================
MARIA DB CLOUD SSL
=========================================================
*/

$sslCA = getenv('DB_SSL_CA') ?: '/etc/secrets/globalsignrootca.pem';

if (!is_file($sslCA)) {
    die(
        'DB ERROR: SSL certificate not found at: ' .
        $sslCA
    );
}

/*
=========================================================
CONFIGURE SSL
=========================================================
*/

$sslConfigured = mysqli_ssl_set(
    $conn,
    null,
    null,
    $sslCA,
    null,
    null
);

if (!$sslConfigured) {
    die('DB ERROR: Could not configure SSL certificate.');
}

/*
=========================================================
CONNECT TO MARIA DB CLOUD
=========================================================
*/

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
