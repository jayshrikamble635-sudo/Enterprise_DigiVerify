<?php

session_start();
include("../database/config.php");

if(!isset($_SESSION['subadmin_id'])){
    header("Location:login.php");
    exit();
}

if(isset($_GET['id'])){

$id=(int)$_GET['id'];

mysqli_query($conn,"
UPDATE documents
SET status='Rejected'
WHERE id='$id'
");

}

header("Location:verify.php");
exit();

?>