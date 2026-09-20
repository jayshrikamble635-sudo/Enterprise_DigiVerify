<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}

/* Reports Data */

$total=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM documents
"))['c'];

$approved=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM documents
WHERE status='Approved'
"))['c'];

$pending=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM documents
WHERE status='Pending'
"))['c'];

$rejected=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM documents
WHERE status='Rejected'
"))['c'];
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Reports</title>

<link rel="stylesheet" href="../css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">

<div class="top-header">

<div class="header-left">

<h1>Reports</h1>

<p>

Document Verification Reports

</p>

</div>

<div class="header-right">

<div class="admin-profile">

<div class="profile-icon">

<i class="fas fa-chart-column"></i>

</div>

<div>

<h4>Reports</h4>

<span>Enterprise DigiVerify</span>

</div>

</div>

</div>

</div>

<div class="stats-grid">