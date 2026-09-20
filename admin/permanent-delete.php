<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$id=(int)$_GET['id'];

mysqli_query($conn,"
DELETE FROM documents
WHERE id='$id'
");

header("Location: deleted-documents.php");
exit();
?>