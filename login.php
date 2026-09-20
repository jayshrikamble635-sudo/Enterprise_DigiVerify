<?php
session_start();
include("database/config.php");

$message = "";

if(isset($_POST['login']))
{

$username = mysqli_real_escape_string($conn,$_POST['username']);
$password = mysqli_real_escape_string($conn,$_POST['password']);

$result = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$username'");
if(mysqli_num_rows($result)==0)
{

$message="<div class='error'>Email or Username is not registered.</div>";

}
else
{

$user = mysqli_fetch_assoc($result);

if(password_verify($password,$user['password']))
{

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];

    if($user['role'] == "admin")
    {
        $_SESSION['admin_id'] = $user['id'];
        header("Location: admin/dashboard.php");
    }
    elseif($user['role'] == "subadmin")
    {
        $_SESSION['subadmin_id'] = $user['id'];
        header("Location: subadmin/dashboard.php");
    }
    else
    {
        header("Location: dashboard.php");
    }

    exit();

}
else
{
    $message = "<div class='error'>Incorrect Password.</div>";
}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | DigiVerify Enterprise</title>

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

<a href="register.php" class="btn-outline">

Register

</a>

</div>

</nav>

</header>

<section class="auth-section">

<div class="auth-card">

<div class="left-panel">

<h1>Welcome Back</h1>

<p>

Login to access your DigiVerify dashboard, upload documents, track verification status, and securely manage your digital identity.

</p>

<div class="feature">

<i class="fa-solid fa-shield-halved"></i>

<span>Enterprise Security</span>

</div>

<div class="feature">

<i class="fa-solid fa-file-circle-check"></i>

<span>Fast Document Verification</span>

</div>

<div class="feature">

<i class="fa-solid fa-user-lock"></i>

<span>Private User Dashboard</span>

</div>

</div>
<div class="right-panel">

<h2>User Login</h2>

<?php echo $message; ?>

<form method="POST" autocomplete="off">

<div class="input-box">

<i class="fa-solid fa-user"></i>

<input
type="text"
name="username"
placeholder="Username"
autocomplete="off"
required>
</div>

<div class="input-box">

<i class="fa-solid fa-lock"></i>
<input
type="password"
name="password"
placeholder="Password"
autocomplete="new-password"
required>

</div>

<div class="remember-box">

<label>

<input type="checkbox">

Remember Me

</label>

<a href="forgot_password.php">

Forgot Password?

</a>

</div>

<button
type="submit"
name="login"
class="register-btn">

Login

</button>

<p class="bottom-text">

Don't have an account?

<a href="register.php">

Register

</a>

</p>

</form>

</div>

</div>

</section>

</body>

</html>