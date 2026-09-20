<?php
session_start();
include "config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    $row = mysqli_fetch_assoc($result);

    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    if($row['role'] == "admin"){
        header("Location: admin/dashboard.html");
    }
    elseif($row['role'] == "subadmin"){
        header("Location: subadmin/dashboard.html");
    }
    else{
        header("Location: user/dashboard.html");
    }

}else{

    echo "<script>
            alert('Invalid Username or Password');
            window.history.back();
          </script>";

}
?>