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

if(isset($_POST['update'])){

    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $role = mysqli_real_escape_string($conn,$_POST['role']);

    mysqli_query($conn,"
    UPDATE users
    SET
    fullname='$fullname',
    email='$email',
    username='$username',
    role='$role'
    WHERE id='$id'
    ");

    header("Location: users.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit User</title>

<link rel="stylesheet" href="css/admin.css">

<style>

.edit-card{

max-width:700px;
margin:auto;
background:#111a35;
padding:30px;
border-radius:15px;
box-shadow:0 0 20px rgba(0,212,255,.3);

}

.edit-card h2{

margin-bottom:25px;
color:#00d4ff;

}

.form-group{

margin-bottom:20px;

}

.form-group label{

display:block;
margin-bottom:8px;
font-weight:bold;

}

.form-group input,
.form-group select{

width:100%;
padding:12px;
border:none;
border-radius:8px;

}

.save-btn{

background:#00d4ff;
color:#000;
padding:12px 25px;
border:none;
border-radius:8px;
font-weight:bold;
cursor:pointer;

}

.back-btn{

display:inline-block;
margin-left:15px;
color:white;
text-decoration:none;

}

</style>

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="edit-card">

<h2>✏ Edit User</h2>

<form method="POST">

<div class="form-group">

<label>Full Name</label>

<input
type="text"
name="fullname"
value="<?php echo $user['fullname']; ?>"
required>

</div>

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo $user['email']; ?>"
required>

</div>

<div class="form-group">

<label>Username</label>

<input
type="text"
name="username"
value="<?php echo $user['username']; ?>"
required>

</div>

<div class="form-group">

<label>Role</label>

<select name="role">

<option value="user"
<?php if($user['role']=="user") echo "selected"; ?>>

User

</option>

<option value="admin"
<?php if($user['role']=="admin") echo "selected"; ?>>

Admin

</option>

</select>

</div>

<button
type="submit"
name="update"
class="save-btn">

💾 Update User

</button>

<a href="users.php" class="back-btn">

⬅ Back

</a>

</form>

</div>

</div>

</body>

</html>