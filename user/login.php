<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// यदि यूजर पहले से लॉग इन है तो सीधे यूजर डैशबोर्ड पर भेजें
if (isset($_SESSION['user_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

// USER LOGIN BYPASS FOR COLLEGE PRESENTATION (0% ERROR)
if (isset($_POST['user_login'])) {
    // प्रेजेंटेशन के लिए बिना डेटाबेस एरर के सीधे सेशन वैरियेबल्स सेट करें
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_email'] = trim($_POST['email']);
    $_SESSION['user_name'] = "DigiVerify User";

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - DigiVerify</title>
    <style>
        body { background: #0f172a; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { width: 400px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,.3); box-sizing: border-box; }
        .login-box h2 { text-align: center; margin-bottom: 30px; color: #2563eb; font-weight: bold; }
        label { font-size: 14px; color: #475569; font-weight: 600; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; box-sizing: border-box; outline: none; }
        input:focus { border-color: #2563eb; }
        button { width: 100%; padding: 14px; background: #2563eb; border: none; color: white; font-size: 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        button:hover { background: #1d4ed8; }
        .back-home { text-align: center; margin-top: 20px; }
        .back-home a { color: #2563eb; text-decoration: none; font-size: 14px; font-weight: 500; }
        .back-home a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>User Login</h2>
        
        <!-- autocomplete="off" ब्राउज़र को पुराने रिकॉर्ड थोपने से रोकता है -->
        <form method="POST" autocomplete="off">
            
            <!-- क्रोम के ज़बरदस्ती ऑटो-फ़िल करने वाले बोट्स को भटकाने के लिए डमी फ़ील्ड्स -->
            <input type="text" name="fake_user_field" style="display:none;" aria-hidden="true">
            <input type="password" name="fake_pass_field" style="display:none;" aria-hidden="true">

            <label>Email</label>
            <!-- autocomplete="new-password" सुनिश्चित करता है कि इनपुट बॉक्स साफ़ रहे -->
            <input type="email" name="email" placeholder="Enter your email" autocomplete="new-password" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" autocomplete="new-password" required>

            <button type="submit" name="user_login">Login</button>
            
            <div class="back-home">
                <a href="../index.php">← Back to Home</a>
            </div>
        </form>
    </div>
</body>
</html>
