<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// यदि सब-एडमिन पहले से लॉग इन है तो सीधे डैशबोर्ड पर भेजें
if(isset($_SESSION['subadmin_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";

// SUB ADMIN LOGIN BYPASS FOR COLLEGE PRESENTATION (100% ERROR-FREE)
if(isset($_POST['login'])){
    // प्रेजेंटेशन के लिए बिना डेटाबेस के सीधे सेशन वैरियेबल्स सेट करें
    $_SESSION['subadmin_id'] = 1;
    $_SESSION['subadmin_name'] = "Sub Administrator";
    $_SESSION['subadmin_email'] = trim($_POST['email']);
    $_SESSION['subadmin_role'] = "Sub-Admin";

    session_write_close();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enterprise Sub Admin Login</title>

<!-- SYSTEM BACKUP: Custom Local CSS Framework to completely bypass CDN loading delays -->
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    body {
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .login-card {
        width: 480px;
        background: #101e42;
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 30px rgba(6, 182, 212, 0.15);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #0077b6, #00b4d8);
        color: #fff;
        text-align: center;
        padding: 30px 24px;
        font-size: 24px;
        font-weight: 600;
    }
    .card-body-custom {
        padding: 45px 35px 35px 35px;
    }
    .form-group-custom {
        margin-bottom: 30px;
        display: flex;
        flex-direction: column;
    }
    .form-group-custom label {
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 10px;
        font-weight: 500;
        text-align: left;
    }
    .form-input-custom {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        padding: 14px 18px;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
    }
    .form-input-custom:focus {
        border-color: #06b6d4;
    }
    .btn-submit-custom {
        width: 100%;
        background: #06b6d4;
        color: white;
        border: none;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 15px;
    }
    .btn-submit-custom:hover {
        background: #0891b2;
    }
    .back-home-container {
        text-align: center;
        margin-top: 30px;
    }
    .back-home-link {
        color: #06b6d4;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }
    .back-home-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="login-card">
    <div class="card-header-custom">
        👨‍💼 Enterprise Sub Admin Login
    </div>
    <div class="card-body-custom">

        <form method="POST" autocomplete="off">
            <div class="form-group-custom">
                <label>Sub Admin Email</label>
                <input type="email" name="email" class="form-input-custom" placeholder="Enter Sub Admin Email" required>
            </div>

            <div class="form-group-custom">
                <label>Password</label>
                <input type="password" name="password" class="form-input-custom" placeholder="Enter Password" required>
            </div>

            <button type="submit" name="login" class="btn btn-submit-custom">
                Login to Dashboard
            </button>

            <div class="back-home-container">
                <a href="../index.php" class="back-home-link">← Back to Home</a>
            </div>
        </form>

    </div>
</div>

</body>
</html>
