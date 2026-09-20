<?php
session_start();
include("database/config.php");

$message = "";

if(isset($_POST['register']))
{

$fullname=mysqli_real_escape_string($conn,$_POST['fullname']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$username=mysqli_real_escape_string($conn,$_POST['username']);
$password=mysqli_real_escape_string($conn,$_POST['password']);
$confirm=mysqli_real_escape_string($conn,$_POST['confirm_password']);

if($password!=$confirm)
{
$message="<div class='error'>Passwords do not match.</div>";
}
else
{

$check=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$email'");

if(mysqli_num_rows($check)>0)
{
$message="<div class='error'>Username or Email already exists.</div>";
}
else
{

$pass=password_hash($password,PASSWORD_DEFAULT);

mysqli_query($conn,"INSERT INTO users(fullname,email,username,password)
VALUES('$fullname','$email','$username','$pass')");

$message="<div class='success'>Registration Successful. Please Login.</div>";

}

}

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | DigiVerify Enterprise</title>

<link rel="stylesheet" href="css/auth.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="bg-grid"></div>

<div class="gradient-one"></div>

<div class="gradient-two"></div>
<header>

<nav class="navbar">

<div class="logo">

<i class="fa-solid fa-shield-halved"></i>

<h2>DigiVerify</h2>

</div>

<ul>

<li><a href="index.php">Home</a></li>

<li><a href="about.php">About</a></li>

<li><a href="services.php">Services</a></li>

<li><a href="contact.php">Contact</a></li>

</ul>

<div class="nav-buttons">

<a href="login.php" class="btn-outline">Login</a>

</div>

</nav>

</header>

<section class="auth-section">

<div class="auth-card">

<div class="left-panel">

<h1>Create Account</h1>

<p>

Join DigiVerify Enterprise and securely manage your digital identity and document verification.

</p>

<div class="feature">

<i class="fa-solid fa-user-check"></i>

<span>Secure Registration</span>

</div>

<div class="feature">

<i class="fa-solid fa-file-shield"></i>

<span>Encrypted Document Storage</span>

</div>

<div class="feature">

<i class="fa-solid fa-lock"></i>

<span>Protected User Dashboard</span>

</div>

</div>
<div class="right-panel">

<h2>Register</h2>

<?php echo $message; ?>

<form method="POST">

<div class="input-box">

<i class="fa-solid fa-user"></i>

<input
type="text"
name="fullname"
placeholder="Full Name"
required>

</div>

<div class="input-box">

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Email Address"
required>

</div>

<div class="input-box">

<i class="fa-solid fa-user-tag"></i>

<input
type="text"
name="username"
placeholder="Username"
required>

</div>

<div class="input-box">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
placeholder="Password"
required>

</div>

<div class="input-box">

<i class="fa-solid fa-key"></i>

<input
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>

</div>

<button
type="submit"
name="register"
class="register-btn">

Create Account

</button>

<p class="bottom-text">

Already have an account?

<a href="login.php">

Login

</a>

</p>

</form>

</div>

</div>

</section>

</body>

</html>