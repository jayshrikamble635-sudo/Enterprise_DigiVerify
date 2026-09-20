<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../database/config.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $role = mysqli_real_escape_string($conn,$_POST['role']);

    mysqli_query($conn,"UPDATE users SET
        fullname='$fullname',
        email='$email',
        username='$username',
        role='$role'
        WHERE id='$id'");

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
</head>

<body>

<h2>Edit User</h2>

<form method="POST">

<p>
Full Name<br>
<input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
</p>

<p>
Email<br>
<input type="email" name="email" value="<?php echo $user['email']; ?>" required>
</p>

<p>
Username<br>
<input type="text" name="username" value="<?php echo $user['username']; ?>" required>
</p>

<p>
Role<br>

<select name="role">

<option value="admin" <?php if($user['role']=="admin") echo "selected"; ?>>Admin</option>

<option value="subadmin" <?php if($user['role']=="subadmin") echo "selected"; ?>>Sub Admin</option>

<option value="user" <?php if($user['role']=="user") echo "selected"; ?>>User</option>

</select>

</p>

<button type="submit" name="update">
Update User
</button>

</form>

<br>

<a href="users.php">⬅ Back</a>

</body>
</html>