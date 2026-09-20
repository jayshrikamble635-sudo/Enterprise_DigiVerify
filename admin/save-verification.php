<?php

include("../database/config.php");

$id = $_POST['id'];

$quality = $_POST['quality'];
$qr_status = $_POST['qr_status'];
$result = $_POST['result'];
$remarks = $_POST['remarks'];

// Result ke hisaab se status set hoga
$fraud_score = 0;

if($quality=="Blur")
{
    $fraud_score += 30;
}

if($quality=="Damaged")
{
    $fraud_score += 50;
}

if($qr_status=="Invalid")
{
    $fraud_score += 40;
}

if($qr_status=="Not Found")
{
    $fraud_score += 20;
}

if($result=="Fake")
{
    $fraud_score = 100;
}
$status = "Pending";

if($result=="Original")
{
    $status="Approved";
}
elseif($result=="Fake")
{
    $status="Rejected";
}
else
{
    $status="Pending";
}

$sql="UPDATE documents SET

quality='$quality',
qr_status='$qr_status',
result='$result',
remarks='$remarks',
status='$status',
fraud_score='$fraud_score'

WHERE id='$id'";

if(mysqli_query($conn,$sql))
{
    header("Location:dashboard.php");
}
else
{
    echo "Database Error : ".mysqli_error($conn);
}

?>