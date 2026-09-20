<?php

session_start();

include("database/config.php");


if(!isset($_SESSION['user_id'])){

    header("Location: user/login.php");
    exit();

}


$user_id=$_SESSION['user_id'];


$result=mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
");


$user=mysqli_fetch_assoc($result);


?>


<!DOCTYPE html>
<html>

<head>

<title>User Profile</title>
<link rel="stylesheet" href="css/style.css">


<style>

body{

background:#050b1f;
color:white;
font-family:Arial;

}


.box{

width:400px;
margin:50px auto;
background:#111a35;
padding:30px;
border-radius:20px;
box-shadow:0 0 20px #00d4ff;
text-align:center;

}


h1{

color:#00d4ff;

}


.info{

background:#16234a;
padding:12px;
margin:10px;
border-radius:10px;

}


a{

color:#00d4ff;
text-decoration:none;

}

</style>

</head>


<body>


<div class="box">


<h1>👤 My Profile</h1>


<div class="info">

User ID :
<?php echo $user['id']; ?>

</div>


<div class="info">

Email :
<?php echo $user['email']; ?>

</div>


<div class="info">

Created :
<?php echo $user['created_at']; ?>

</div>



<br>


<a href="dashboard.php">

⬅ Back Dashboard

</a>


</div>


</body>

</html>