<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | DigiVerify Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background-color: #0b0f19; color: #f8fafc; display: flex; min-height: 100vh; }
        .sidebar { width: 240px; background: rgba(15, 23, 42, 0.9); border-right: 1px solid rgba(255, 255, 255, 0.05); padding: 30px 20px; display: flex; flex-direction: column; position: fixed; height: 100vh; text-align: left; }
        .sidebar h2 { font-size: 22px; color: white; }
        .sidebar-menu a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-top: 5px; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #1e293b; color: #38bdf8; font-weight: 600; }
        .main-content { margin-left: 240px; flex: 1; padding: 40px; text-align: left; }
        .profile-card { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 35px; width: 500px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 18px 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 15px; color: #ffffff; }
        td:first-child { font-weight: 600; color: #38bdf8; text-transform: uppercase; font-size: 13px; }
        .role-badge { background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        .btn-action { width: 100%; padding: 12px; margin-top: 15px; background: #2563eb; border: none; color: white; font-weight: bold; border-radius: 8px; cursor: pointer; text-align: center; display: block; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>DigiVerify</h2>
        <nav class="sidebar-menu" style="margin-top:30px;">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-documents.php">Documents</a>
            <a href="verify-documents.php">Verify Documents</a>
            <a href="manage-users.php">Users</a>
            <a href="notifications.php">Notifications</a>
            <a href="profile.php" class="active">Profile</a>
        </nav>
    </div>
    <div class="main-content">
        <h1>Admin Profile</h1>
        <div class="profile-card">
            <table>
                <tbody>
                    <tr><td>Admin ID</td><td>1</td></tr>
                    <tr><td>Name</td><td>Administrator</td></tr>
                    <tr><td>Email Address</td><td>admin@digiverify.com</td></tr>
                    <tr><td>System Role</td><td><span class="role-badge">Super Admin</span></td></tr>
                </tbody>
            </table>
            <a href="#" class="btn-action">Edit Profile</a>
            <a href="#" class="btn-action" style="background:#1e1b29;border:1px solid #3b1820;color:#ef4444;">Change Password</a>
        </div>
    </div>
</body>
</html>
