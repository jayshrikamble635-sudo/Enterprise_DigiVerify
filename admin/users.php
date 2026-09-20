<?php

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include("../database/config.php");


$query = "SELECT id, fullname, email, username, role, created_at 
          FROM users 
          ORDER BY id DESC";


$result = mysqli_query($conn,$query);


?>


<!DOCTYPE html>
<html>

<head>

<title>User Management</title>

<link rel="stylesheet" href="css/admin.css">

</head>


<body>


<?php include("includes/sidebar.php"); ?>


<div class="main-content">


<div class="top-header">

<div class="header-left">

<h1>👥 User Management</h1>

<p>Manage registered users</p>

</div>


<div class="admin-profile">

<div class="profile-icon">
👤
</div>

<div>

<h4>Administrator</h4>

<span>Enterprise DigiVerify</span>

</div>

</div>


</div>




<div class="table-card">


<div class="table-header">

<h3>All Users</h3>

</div>



<table>


<thead>

<tr>

<th>Name</th>

<th>Email</th>

<th>Username</th>

<th>Role</th>

<th>Created</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php

if(mysqli_num_rows($result)>0){


while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>
<?php echo $row['fullname']; ?>
</td>


<td>
<?php echo $row['email']; ?>
</td>


<td>
<?php echo $row['username']; ?>
</td>


<td>

<span class="status approved">

<?php echo $row['role']; ?>

</span>

</td>


<td>
<?php echo $row['created_at']; ?>
</td>


<td>

<a href="#" class="view-btn">
View
</a>

</td>


</tr>


<?php

}

}

else{

echo "

<tr>
<td colspan='6'>
No Users Found
</td>
</tr>

";

}


?>


</tbody>


</table>


</div>


</div>


</body>

</html>