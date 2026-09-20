<?php
session_start();

include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";

if(isset($_POST['change_password']))
{

$current = mysqli_real_escape_string($conn,$_POST['current_password']);
$new = mysqli_real_escape_string($conn,$_POST['new_password']);
$confirm = mysqli_real_escape_string($conn,$_POST['confirm_password']);


$query = mysqli_query($conn,"
SELECT password 
FROM users 
WHERE id='$user_id'
");


$user = mysqli_fetch_assoc($query);


if($current == $user['password'])
{

if($new == $confirm)
{

mysqli_query($conn,"
UPDATE users 
SET password='$new'
WHERE id='$user_id'
");


$message = "Password Updated Successfully";

}
else
{
$message = "New Password and Confirm Password Not Match";
}

}
else
{
$message = "Current Password Incorrect";
}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>User Settings</title>

<link rel="stylesheet" href="css/style.css">

</head>


<body>


<div class="section-card">


<h2>⚙️ User Settings</h2>


<?php echo $message; ?>


<h3>🔑 Change Password</h3>


<form method="post">


<label>Current Password</label>

<input type="password" name="current_password" placeholder="Enter Current Password">


<br>


<label>New Password</label>

<input type="password" name="new_password" placeholder="Enter New Password">


<br>


<label>Confirm Password</label>

<input type="password" name="confirm_password" placeholder="Confirm Password">


<br><br>


<button type="submit" name="change_password">
Save Password
</button>


</form>



<hr>


<h3>🔔 Notification Settings</h3>


<label>
<input type="checkbox" checked>
Email Notifications
</label>

<br>


<label>
<input type="checkbox" checked>
Document Verification Alerts
</label>


<br>


<label>
<input type="checkbox" checked>
Login Alerts
</label>



</div>


</body>

</html>