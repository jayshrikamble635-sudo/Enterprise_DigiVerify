<?php
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include("../database/config.php");

$message="";

if(isset($_POST['save'])){

    $message="Settings Saved Successfully";

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Settings</title>

<link rel="stylesheet" href="../css/admin.css">

<link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>


<body>


<div class="admin-container">


<?php include("sidebar.php"); ?>


<div class="main-content">


<h1>
<i class="fa-solid fa-gear"></i>
Settings
</h1>


<?php

if($message!=""){
echo "<div class='success'>$message</div>";
}

?>


<form method="POST">


<div class="section-card">


<h2>
🌙 Theme Settings
</h2>


<div class="setting-box">

<label>
Choose Theme
</label>


<select name="theme">

<option>
Dark Mode
</option>

<option>
Light Mode
</option>

</select>


</div>


</div>





<div class="section-card">


<h2>
🔑 Change Password
</h2>


<div class="setting-box">


<label>
Current Password
</label>

<input type="password"
placeholder="Enter Current Password">



<label>
New Password
</label>

<input type="password"
placeholder="Enter New Password">



<label>
Confirm Password
</label>

<input type="password"
placeholder="Confirm Password">


</div>


</div>






<div class="section-card">


<h2>
🔔 Notification Settings
</h2>



<div class="setting-box">


<label>

<input type="checkbox" checked>

Email Notifications

</label>


<br>


<label>

<input type="checkbox">

Document Verification Alerts

</label>


<br>


<label>

<input type="checkbox" checked>

Login Alerts

</label>



</div>


</div>







<div class="section-card">


<button name="save"
class="save-btn">


<i class="fa-solid fa-floppy-disk"></i>

Save Settings


</button>


</div>



</form>


</div>


</div>


</body>

</html>