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
    <title>System Notifications | DigiVerify Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background-color: #0b0f19; color: #f8fafc; display: flex; min-height: 100vh; }
        .sidebar { width: 240px; background: rgba(15, 23, 42, 0.9); border-right: 1px solid rgba(255, 255, 255, 0.05); padding: 30px 20px; display: flex; flex-direction: column; position: fixed; height: 100vh; text-align: left;}
        .sidebar-logo h2 { font-size: 22px; color: #ffffff; }
        .menu-list { list-style: none; display: flex; flex-direction: column; gap: 8px; margin-top: 30px; }
        .menu-item a { display: block; color: #cbd5e1; text-decoration: none; padding: 12px 16px; border-radius: 10px; font-size: 14px; }
        .menu-item.active a, .menu-item a:hover { background: #1e293b; color: #38bdf8; font-weight: 600; }
        .main-content { margin-left: 240px; flex: 1; padding: 40px; text-align: left; }
        .top-header { border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px; margin-bottom: 30px; }
        .notification-card { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; padding: 20px 25px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
        .notif-info h3 { font-size: 16px; color: #ffffff; }
        .notif-info p { font-size: 14px; color: #cbd5e1; margin-top: 4px; }
        .notif-date { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo"><h2>DigiVerify</h2><p style="color:#38bdf8;font-size:11px;">Admin Edge</p></div>
        <ul class="menu-list">
            <li class="menu-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="menu-item"><a href="manage-documents.php">Documents</a></li>
            <li class="menu-item"><a href="verify-documents.php">Verify Documents</a></li>
            <li class="menu-item"><a href="manage-users.php">Users</a></li>
            <li class="menu-item active"><a href="notifications.php">Notifications</a></li>
            <li class="menu-item"><a href="profile.php">Profile</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="top-header"><h1>Notifications</h1><p>Latest Core System Audits</p></div>
        <div class="notification-card">
            <div class="notif-info"><h3>New User Registration</h3><p>A new tenant user account has registered successfully.</p></div>
            <div class="notif-date">12 Aug 2026 16:03</div>
        </div>
        <div class="notification-card">
            <div class="notif-info"><h3>Document Verification Completed</h3><p>Document structural analysis matches node database.</p></div>
            <div class="notif-date">12 Aug 2026 16:03</div>
        </div>
    </div>
</body>
</html>
