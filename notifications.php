<?php
session_start();
include("database/config.php");
if(!isset($_SESSION['user_id'])){
    header("Location: user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Mark notification as read

if(isset($_GET['read'])){
    
    $id = $_GET['read'];

    mysqli_query($conn,"
    UPDATE notifications 
    SET is_read=1, status='Read'
    WHERE id='$id' AND user_id='$user_id'
    ");

    header("Location: notifications.php");
    exit();
}



$result = mysqli_query($conn,"
SELECT * FROM notifications
WHERE user_id='$user_id'
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>DigiVerify Notifications</title>
<link rel="stylesheet" href="css/style.css">

<style>

body{
font-family:Arial;
background:#050b1f;
color:white;
}

.container{
width:80%;
margin:40px auto;
}

.card{

background:#111a35;
padding:20px;
margin-bottom:15px;
border-radius:15px;
box-shadow:0 0 15px #00eaff;

}

.unread{
border-left:5px solid #00ff99;
}


button{

background:#00eaff;
border:none;
padding:8px 15px;
border-radius:20px;
cursor:pointer;

}

a{
color:white;
text-decoration:none;
}


</style>

</head>

<body>


<div class="container">

<h1>🔔 Notifications</h1>


<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){


$class="";

if($row['is_read']==0){
$class="unread";
}


?>


<div class="card <?php echo $class; ?>">


<h3>
<?php echo $row['title']; ?>
</h3>


<p>
<?php echo $row['message']; ?>
</p>


<small>
<?php echo $row['created_at']; ?>
</small>


<br><br>


<?php

if($row['is_read']==0){

?>

<a href="notifications.php?read=<?php echo $row['id']; ?>">
<button>
Mark as Read
</button>
</a>

<?php

}

?>


</div>


<?php

}

}
else{

echo "<h3>No Notifications Found</h3>";

}

?>


<br>

<a href="dashboard.php">
⬅ Back Dashboard
</a>


</div>


</body>
</html>