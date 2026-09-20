<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){

$id = mysqli_real_escape_string($conn,$_GET['id']);

$query = mysqli_query($conn,"
UPDATE documents 
SET status='Approved'
WHERE id='$id'
");


if($query){

    header("Location: dashboard.php?msg=approved");
    exit();

}else{

    echo "Error updating status";

}

}

?>