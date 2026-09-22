<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify Sub-Admin - Profile</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 0; display: flex; }
        .sidebar { width: 240px; background: #081225; height: 100vh; padding: 30px 20px; box-sizing: border-box; position: fixed; border-right: 1px solid #102a45; }
        .logo-area { font-size: 24px; font-weight: bold; color: #fff; margin-bottom: 5px; }
        .logo-sub { font-size: 11px; color: #00d2ff; font-weight: bold; letter-spacing: 1px; margin-bottom: 40px; }
        .menu-title { font-size: 11px; color: #475569; text-transform: uppercase; font-weight: bold; margin-bottom: 15px; }
        .menu-item { display: block; padding: 12px 15px; color: #94a3b8; text-decoration: none; border-radius: 8px; font-size: 14px; margin-bottom: 8px; }
        .menu-item.active { background: #1e293b; color: #00d2ff; font-weight: 600; }
        .menu-item:hover { background: rgba(30, 41, 59, 0.5); color: #fff; }
        
        .main-content { margin-left: 240px; padding: 40px; flex: 1; min-height: 100vh; box-sizing: border-box; background: #040d1a; }
        .top-badge { background: rgba(56, 189, 248, 0.1); border: 1px solid #38bdf8; color: #38bdf8; font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
        .welcome-title { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
        .welcome-sub { font-size: 14px; color: #64748b; margin-bottom: 30px; }
        
        .profile-card { background: rgba(11, 21, 40, 0.6); border: 1px solid #102a45; border-radius: 12px; padding: 35px; max-width: 650px; box-shadow: 0 8px 25px rgba(0,0,0,0.5); }
        .profile-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #102a45; font-size: 15px; }
        .profile-row:last-child { border: none; }
        .label-text { color: #475569; font-weight: bold; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        .value-text { color: #cbd5e1; font-weight: 500; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-area">DigiVerify</div>
        <div class="logo-sub">SUB-ADMIN CORE</div>
        <div class="menu-title">Main Menu</div>
        <a href="dashboard.php" class="menu-item">Dashboard</a>
        <a href="documents.php" class="menu-item">Documents</a>
        <a href="verify.php" class="menu-item">Verify Documents</a>
        <a href="notifications.php" class="menu-item">Notifications</a>
        <a href="profile.php" class="menu-item active">Profile</a>
        <a href="logout.php" class="menu-item" style="color: #ef4444; border-top: 1px solid #102a45; margin-top: 20px; padding-top: 15px;">Sub-Admin Logout</a>
    </div>

    <div class="main-content">
        <div class="top-badge">Identity Node</div>
        <div class="welcome-title">Sub Administrator Profile</div>
        <div class="welcome-sub">Active cryptographic credentials and console parameters.</div>

        <div class="profile-card">
            <div class="profile-row">
                <span class="label-text">Assigned UID</span>
                <span class="value-text" style="color: #00d2ff;">#SUB-NODE-2026</span>
            </div>
            <div class="profile-row">
                <span class="label-text">Official Name</span>
                <span class="value-text"><?php echo htmlspecialchars($_SESSION['subadmin_name'] ?? 'Sub Administrator'); ?></span>
            </div>
            <div class="profile-row">
                <span class="label-text">Secure Email Endpoint</span>
                <span class="value-text"><?php echo htmlspecialchars($_SESSION['subadmin_email'] ?? 'subadmin@digiverify.live'); ?></span>
            </div>
            <div class="profile-row">
                <span class="label-text">Node Privilege Role</span>
                <span class="value-text" style="color: #34d399;">Compliance Inspector</span>
            </div>
            <div class="profile-row">
                <span class="label-text">Global Sync Cluster</span>
                <span class="value-text">Enterprise DigiVerify Cluster Node 1</span>
            </div>
        </div>
    </div>
</body>
</html>
