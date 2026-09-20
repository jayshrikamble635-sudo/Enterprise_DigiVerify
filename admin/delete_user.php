<?php
session_start();
include("../database/config.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = (int)$_GET['id'];

// Admin apna khud ka account delete na kar sake
if ($id == $_SESSION['admin_id']) {
    echo "<script>
            alert('You cannot delete your own admin account!');
            window.location='users.php';
          </script>";
    exit();
}

// Check user exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

if (mysqli_num_rows($check) == 0) {
    header("Location: users.php");
    exit();
}

// Delete user
mysqli_query($conn, "DELETE FROM users WHERE id='$id'");

header("Location: users.php?msg=deleted");
exit();
?>