<?php
session_start();
include("../database/config.php");

echo "Connected <br>";

$id = (int)$_GET['id'];

$sql = "UPDATE documents SET is_deleted=0 WHERE id='$id'";

if(mysqli_query($conn,$sql)){
    echo "Update Success";
}else{
    echo "Error : ".mysqli_error($conn);
}

exit();
?>