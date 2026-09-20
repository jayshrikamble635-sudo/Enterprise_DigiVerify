<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: users.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");

if(mysqli_num_rows($result)==0){
    die("User Not Found");
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View User</title>

<link rel="stylesheet" href="css/admin.css">

<style>

.profile-card{

background:#111a35;
padding:30px;
border-radius:15px;
box-shadow:0 0 20px rgba(0,212,255,.3);
max-width:700px;
margin:auto;

}

.profile-card h2{

color:#00d4ff;
margin-bottom:25px;

}

.info{

display:flex;
justify-content:space-between;
padding:15px;
border-bottom:1px solid #333;

}

.label{

font-weight:bold;
color:#00d4ff;

}

.value{

color:#fff;

}

.back-btn{

display:inline-block;
margin-top:25px;
padding:12px 25px;
background:#00d4ff;
color:#000;
text-decoration:none;
border-radius:8px;
font-weight:bold;

}

</style>

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="profile-card">

<h2>👤 User Details</h2>

<div class="info">

<div class="label">ID</div>

<div class="value"><?php echo $user['id']; ?></div>

</div>

<div class="info">

<div class="label">Full Name</div>

<div class="value"><?php echo $user['fullname']; ?></div>

</div>

<div class="info">

<div class="label">Email</div>

<div class="value"><?php echo $user['email']; ?></div>

</div>

<div class="info">

<div class="label">Username</div>

<div class="value"><?php echo $user['username']; ?></div>

</div>

<div class="info">

<div class="label">Role</div>

<div class="value"><?php echo ucfirst($user['role']); ?></div>

</div>

<div class="info">

<div class="label">Created At</div>

<div class="value"><?php echo $user['created_at']; ?></div>

</div>

<a href="users.php" class="back-btn">

⬅ Back to Users

</a>

</div>

</div>

</body>

</html>