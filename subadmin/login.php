<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['subadmin_id'])){
    header("Location: dashboard.php");
    exit();
}
if(isset($_POST['login'])){
    // बाईपास लॉजिक प्रस्तुति के लिए
    $_SESSION['subadmin_id'] = 1;
    $_SESSION['subadmin_name'] = "Sub Administrator";
    $_SESSION['subadmin_email'] = trim($_POST['email']);
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub-Admin Login | DigiVerify</title>
    <style>
        body { background: #040d1a; font-family: 'Segoe UI', Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { width: 420px; background: #081225; padding: 40px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,.5); border: 1px solid #102a45; box-sizing: border-box; text-align: center; }
        .login-box h2 { margin: 0 0 5px 0; color: #fff; font-size: 24px; font-weight: bold; }
        .login-sub { font-size: 11px; color: #00d2ff; font-weight: bold; letter-spacing: 1px; margin-bottom: 35px; text-transform: uppercase; }
        label { font-size: 12px; color: #475569; font-weight: bold; display: block; margin-bottom: 8px; text-align: left; text-transform: uppercase; }
        input { width: 100%; padding: 14px; margin-bottom: 25px; background: #0b1528; border: 1px solid #102a45; border-radius: 8px; font-size: 14px; color: #fff; box-sizing: border-box; outline: none; }
        input:focus { border-color: #00d2ff; }
        button { width: 100%; padding: 14px; background: #1e293b; border: 1px solid #102a45; color: #00d2ff; font-size: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        button:hover { background: #00d2ff; color: #040d1a; }
        .back-home { text-align: center; margin-top: 25px; }
        .back-home a { color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; }
        .back-home a:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Sub-Admin Gateway</h2>
        <div class="login-sub">Secure Node Access</div>
        
        <form method="POST" autocomplete="off">
            <label>Node Username / Email</label>
            <input type="text" name="email" placeholder="Enter assigned node email" required>

            <label>Security Key / Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit" name="login">Authorize Node Session</button>
            
            <div class="back-home">
                <a href="../index.php">← Return to Global Home</a>
            </div>
        </form>
    </div>
</body>
</html>
