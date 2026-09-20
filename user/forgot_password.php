<?php

session_start();

include("../database/config.php");

$message="";


if(isset($_POST['submit'])){


$email = mysqli_real_escape_string($conn,$_POST['email']);


$result = mysqli_query($conn,"
SELECT * FROM users
WHERE email='$email'
");


if(mysqli_num_rows($result)>0){


$message="<div style='color:lime;'>
Account found. Contact admin to reset password.
</div>";


}
else{


$message="<div style='color:red;'>
Email not registered.
</div>";


}


}

?>


<!DOCTYPE html>
<html>

<head>

<title>Forgot Password</title>


<style>

body{

background:#050b1f;
color:white;
font-family:Arial;

}


.box{

width:400px;
margin:80px auto;
background:#111a35;
padding:30px;
border-radius:15px;
box-shadow:0 0 20px #00d4ff;

}


input,button{

width:100%;
padding:12px;
margin-top:15px;
border-radius:8px;
border:none;

}


button{

background:#00d4ff;
font-weight:bold;
cursor:pointer;

}


a{

color:#00d4ff;

}

</style>


</head>


<body>


<div class="box">


<h2>🔑 Forgot Password</h2>


<?php echo $message; ?>


<form method="POST">


<input 
type="email"
name="email"
placeholder="Enter Email"
required>


<button name="submit">

Submit

</button>


</form>


<br>


<a href="login.php">
⬅ Back Login
</a>


</div>


</body>

</html>