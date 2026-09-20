<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../database/config.php");

// Already Logged In
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// LOGIN
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0){

        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];

        header("Location: dashboard.php");
        exit();

    }else{

        $error = "Invalid Email or Password";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Login</title>

<link rel="stylesheet" href="../css/style.css">

<style>

body{

background:#0f172a;

font-family:Arial;

display:flex;

justify-content:center;

align-items:center;

height:100vh;

margin:0;

}

.login-box{

width:420px;

background:white;

padding:35px;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.3);

}

.login-box h2{

text-align:center;

margin-bottom:25px;

color:#2563eb;

}

input{

width:100%;

padding:13px;

margin-top:8px;

margin-bottom:18px;

border:1px solid #ccc;

border-radius:8px;

font-size:15px;

box-sizing:border-box;

}

button{

width:100%;

padding:14px;

background:#2563eb;

border:none;

color:white;

font-size:16px;

border-radius:8px;

cursor:pointer;

font-weight:bold;

}

button:hover{

background:#1d4ed8;

}

.error{

background:#fee2e2;

color:#b91c1c;

padding:10px;

border-radius:8px;

margin-bottom:20px;

text-align:center;

}

</style>

</head>

<body>

<div class="login-box">

<h2>User Login</h2>

<?php
if(isset($error)){
?>

<div class="error">

<?php echo $error; ?>

</div>

<?php
}
?>

<form method="POST" autocomplete="off">

<label>Email</label>

<input
type="email"
name="email"
placeholder="Enter Email"
autocomplete="off"
spellcheck="false"
required>

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter Password"
autocomplete="new-password"
required>

<button
type="submit"
name="login">

Login

</button>
<br><br>

<a href="forgot_password.php">
Forgot Password?
</a>
<!-- Back to Home Button Code -->
<div style="text-align: center; margin-top: 15px;">
    <a href="../index.php" style="text-align: center; color: #007bff; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-block;">
        ← Back to Home
    </a>
</div>


</form>

</div>

</body>

</html>