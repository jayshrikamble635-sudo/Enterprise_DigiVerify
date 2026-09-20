<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: user/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>
<link rel="stylesheet" href="css/style.css">

<style>
body{
background:#050b1f;
color:white;
font-family:Arial;
}

.box{
width:500px;
margin:50px auto;
background:#111a35;
padding:30px;
border-radius:15px;
box-shadow:0 0 20px #00d4ff;
text-align:center;
}

a{
color:#00d4ff;
text-decoration:none;
}
</style>

</head>

<body>

<div class="box">

<h1>⚙ Settings</h1>

<p>Settings module is ready.</p>

<p>You can add:</p>

<p>✔ Change Password</p>

<p>✔ Update Profile</p>

<p>✔ Dark Mode</p>

<br>

<a href="dashboard.php">⬅ Back Dashboard</a>

</div>

</body>
</html>