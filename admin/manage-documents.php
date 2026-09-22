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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents Management | DigiVerify Master Admin</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #060f1e; color: #f1f5f9; display: flex; }
        .sidebar { width: 240px; background: #0b1329; min-height: 100vh; padding: 25px; box-sizing: border-box; border-right: 1px solid #1e293b; text-align: left; }
        .sidebar h2 { font-size: 24px; margin: 0 0 5px 0; color: #fff; }
        .menu-label { font-size: 11px; color: #475569; font-weight: bold; margin: 25px 0 10px 0; text-transform: uppercase; }
        .sidebar-menu a { display: block; color: #94a3b8; padding: 12px 15px; text-decoration: none; border-radius: 6px; font-size: 14px; margin-bottom: 4px; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #1e293b; color: #3b82f6; font-weight: 600; }
        .main-content { flex: 1; padding: 40px; box-sizing: border-box; }
        .top-navbar { border-bottom: 1px solid #1e293b; padding-bottom: 20px; margin-bottom: 30px; text-align: left; }
        .log-card { background: #0b1329; border: 1px solid #1e293b; border-radius: 12px; padding: 30px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 16px; border-bottom: 1px solid #1e293b; font-size: 14px; }
        th { color: #38bdf8; background: #0f172a; font-size: 13px; text-transform: uppercase; }
        td { color: #cbd5e1; }
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: rgba(34, 197, 94, 0.15); color: #4ade80; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>DigiVerify</h2>
        <div style="color:#38bdf8;font-size:11px;font-weight:bold;margin-bottom:30px;">ADMIN EDGE</div>
        <p class="menu-label">Main Menu</p>
        <div class="sidebar-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-documents.php" class="active">Documents</a>
            <a href="verify-documents.php">Verify Documents</a>
            <a href="manage-users.php">Users</a>
            <a href="notifications.php">Notifications</a>
            <a href="profile.html">Profile</a>
        </div>
    </aside>
    <main class="main-content">
        <header class="top-navbar">
            <h1 style="margin:0;">Welcome Back, Administrator</h1>
            <p style="margin:5px 0 0 0; color:#64748b; font-size:14px;">Enterprise Document Verification Control Center</p>
        </header>
        <div class="log-card">
            <h3 style="text-align:left;color:#fff;margin-bottom:20px;">Recent Verification Activities</h3>
            <table>
                <thead><tr><th>Log ID</th><th>Holder Name</th><th>Document Type</th><th>Timestamp</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td style="color:#38bdf8;font-weight:bold;">#12</td><td><strong>RIDDHI BALAJI KAMBLE</strong></td><td>AADHAAR CARD (UIDAI)</td><td>2026-08-12 23:28:38</td><td><span class="status-badge">APPROVED</span></td></tr>
                    <tr><td style="color:#38bdf8;font-weight:bold;">#11</td><td><strong>IGN CA</strong></td><td>UNKNOWN DOCUMENT</td><td>2026-08-12 22:50:02</td><td><span class="status-badge" style="background:rgba(239,68,68,0.15);color:#f87171;">REJECTED</span></td></tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
