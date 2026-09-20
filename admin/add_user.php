<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../database/config.php");

$message = "";

if(isset($_POST['save'])){

    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $role = mysqli_real_escape_string($conn,$_POST['role']);

    // 🔐 PASSWORD HASH (IMPORTANT FIX)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check duplicate email
    $check = mysqli_query($conn,"SELECT id FROM users WHERE email='$email' LIMIT 1");

    if(mysqli_num_rows($check)>0){

        $message = "<p style='color:red;'>Email already exists.</p>";

    }else{

        $sql = "INSERT INTO users(fullname,email,username,password,role)
                VALUES('$fullname','$email','$username','$password','$role')";

        if(mysqli_query($conn,$sql)){
            $message = "<p style='color:green;'>User Added Successfully.</p>";
        }else{
            $message = "<p style='color:red;'>Error adding user.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
</head>

<body>

<h2>Add New User</h2>

<?php echo $message; ?>

<form method="POST">

<p>
Full Name<br>
<input type="text" name="fullname" required>
</p>

<p>
Email<br>
<input type="email" name="email" required>
</p>

<p>
Username<br>
<input type="text" name="username" required>
</p>

<p>
Password<br>
<input type="password" name="password" required>
</p>

<p>
Role<br>

<select name="role">

<option value="admin">Admin</option>
<option value="subadmin">Sub Admin</option>
<option value="user">User</option>

</select>

</p>

<button type="submit" name="save">
Add User
</button>

</form>

<br>

<a href="users.php">⬅ Back to Manage Users</a>

</body>
</html>