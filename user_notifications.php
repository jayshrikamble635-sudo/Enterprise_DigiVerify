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
FROM notifications
WHERE user_id='$user_id'
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>User Notifications</title>

<link rel="stylesheet" href="css/style.css">

</head>


<body>


<div class="section-card">


<h2>🔔 My Notifications</h2>


<table border="1" width="100%">


<tr>

<th>Message</th>
<th>Status</th>
<th>Date</th>

</tr>


<?php


if(mysqli_num_rows($query)>0)
{


while($row=mysqli_fetch_assoc($query))
{


?>


<tr>


<td>
<?php echo $row['message']; ?>
</td>


<td>
<?php echo $row['status']; ?>
</td>


<td>
<?php echo $row['created_at']; ?>
</td>


</tr>


<?php

}

}

else
{

echo "

<tr>
<td colspan='3'>
No Notifications Found
</td>
</tr>

";

}


?>


</table>


</div>


</body>

</html>