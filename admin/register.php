<?php
session_start();
include("../database/config.php");

$id = (int)$_GET['id'];

$verified_by = isset($_SESSION['admin_email'])
    ? $_SESSION['admin_email']
    : "Admin";

mysqli_query($conn,"
UPDATE documents
SET
status='Rejected',
verified_by='$verified_by',
verified_at=NOW()
WHERE id='$id'
");

header("Location: dashboard.php");
exit();
?>