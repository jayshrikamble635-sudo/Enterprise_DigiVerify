<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$id=(int)$_GET['id'];

mysqli_query($conn,"
UPDATE documents
SET is_deleted=0
WHERE id='$id'
");

header("Location: deleted-documents.php");
exit();
?>