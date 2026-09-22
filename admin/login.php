<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// यदि एडमिन पहले से लॉग इन है तो सीधे डैशबोर्ड पर भेजें
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

// ADMIN LOGIN BYPASS FOR COLLEGE PRESENTATION (0% ERROR)
if (isset($_POST['admin_login'])) {
    // कोई भी ईमेल/पासवर्ड डालने पर सीधे सुंदर एडमिन डैशबोर्ड पर भेजें
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = "Administrator";

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DigiVerify</title>
    <style>
        body { background: #0f172a; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { width: 400px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,.3); box-sizing: border-box; }
        .login-box h2 { text-align: center; margin-bottom: 30px; color: #1e3a8a; font-weight: bold; }
        label { font-size: 14px; color: #475569; font-weight: 600; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; box-sizing: border-box; }
        button { width: 100%; padding: 14px; background: #1e3a8a; border: none; color: white; font-size: 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        button:hover { background: #172554; }
        .back-home { text-align: center; margin-top: 20px; }
        .back-home a { color: #2563eb; text-decoration: none; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Control Login</h2>
        
        <form method="POST" autocomplete="off">
            <label>Admin Username / Email</label>
            <input type="text" name="email" placeholder="Enter Admin Username" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" name="admin_login">Login As Admin</button>
            
            <div class="back-home">
                <a href="../index.php">← Back to Global Home</a>
            </div>
        </form>
    </div>

</body>
</html>
