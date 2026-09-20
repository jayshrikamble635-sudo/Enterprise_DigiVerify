<?php
session_start();
include("../database/config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}

$id = intval($_POST['id']);
$status = $_POST['status'];
$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$verified_by = $_SESSION['admin_name'] ?? "Administrator";
$verified_at = date("Y-m-d H:i:s");

// Status ke hisaab se values
if($status=="Approved"){

    $recommendation = "Verified";
    $result = "Likely Genuine";
    $fraud_score = 5;

}else{

    $recommendation = "Rejected";
    $result = "Suspicious";
    $fraud_score = 90;

}

$sql = "UPDATE documents SET

status='$status',
remarks='$remarks',
recommendation='$recommendation',
result='$result',
fraud_score='$fraud_score',
verified_by='$verified_by',
verified_at='$verified_at'

WHERE id='$id'";
$audit = "INSERT INTO audit_logs
(
document_id,
action,
admin_name,
remarks
)
VALUES
(
'$id',
'$status',
'$verified_by',
'$remarks'
)";

mysqli_query($conn,$audit);
if(mysqli_query($conn,$sql)){

    header("Location: dashboard.php?msg=updated");
    exit();

}else{

    die("Database Error : ".mysqli_error($conn));

}
?>