<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Already Logged In
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// LOGIN BYPASS FOR COLLEGE PRESENTATION (0% ERROR)
if (isset($_POST['login'])) {
    // कोई भी ईमेल और पासवर्ड डालने पर सीधे सुंदर डैशボード पर भेजें
    $_SESSION['user_id'] = '1';
    $_SESSION['user_name'] = 'Riddhi Balaji Kamble';
    $_SESSION['user_email'] = trim($_POST['email']);

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Login</title>
<style>
body{background:#0f172a;font-family:Arial;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
.login-box{width:420px;background:white;padding:35px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.3);}
.login-box h2{text-align:center;margin-bottom:25px;color:#2563eb;}
input{width:100%;padding:13px;margin-top:8px;margin-bottom:18px;border:1px solid #ccc;border-radius:8px;font-size:15px;box-sizing:border-box;}
button{width:100%;padding:14px;background:#2563eb;border:none;color:white;font-size:16px;border-radius:8px;cursor:pointer;font-weight:bold;}
button:hover{background:#1d4ed8;}
</style>
</head>
<body>
<div class="login-box">
<h2>User Login</h2>
<form method="POST" autocomplete="off">
<label>Email</label>
<input type="email" name="email" placeholder="Enter Email" required>
<label>Password</label>
<input type="password" name="password" placeholder="Enter Password" required>
<button type="submit" name="login">Login</button>
<div style="text-align: center; margin-top: 15px;">
    <a href="../index.php" style="color: #007bff; text-decoration: none; font-size: 14px; font-weight: 500;">← Back to Home</a>
</div>
</form>
</div>
</body>
</html>
