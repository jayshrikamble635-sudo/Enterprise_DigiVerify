<?php
session_start();

include("database/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
");


$user = mysqli_fetch_assoc($query);

?>


<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link rel="stylesheet" href="css/style.css">

</head>


<body>


<div class="section-card">


<h2>👤 My Profile</h2>


<table border="1" width="100%">


<tr>

<th>Full Name</th>

<td>
<?php echo $user['fullname']; ?>
</td>

</tr>


<tr>

<th>Email</th>

<td>
<?php echo $user['email']; ?>
</td>

</tr>


<tr>

<th>Username</th>

<td>
<?php echo $user['username']; ?>
</td>

</tr>


<tr>

<th>Account Type</th>

<td>
<?php echo $user['role']; ?>
</td>

</tr>


<tr>

<th>Registration Date</th>

<td>
<?php echo $user['created_at'] ?? "N/A"; ?>
</td>

</tr>


</table>


<br>


<a href="settings.php" class="btn">
⚙️ Settings
</a>


</div>


</body>

</html>