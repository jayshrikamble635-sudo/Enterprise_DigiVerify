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
    <title>Verify Documents Panel | DigiVerify</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #050e1e; color: #e2e8f0; display: flex; }
        .sidebar { width: 240px; background: #0b1528; padding: 20px; box-sizing: border-box; border-right: 1px solid #1e293b; text-align: left; }
        .sidebar h2 { font-size: 22px; color: #fff; }
        .menu-label { font-size: 11px; color: #64748b; font-weight: bold; margin: 20px 0 10px 0; }
        .sidebar-menu a { display: block; color: #94a3b8; padding: 12px; text-decoration: none; border-radius: 6px; font-size: 14px; margin-bottom: 5px; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #1e293b; color: #3b82f6; }
        .main-content { flex: 1; padding: 40px; box-sizing: border-box; }
        .header-panel { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; text-align: left; }
        .verification-panel { background: #0b1528; padding: 30px; border-radius: 12px; border: 1px solid #1e293b; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 16px; border-bottom: 1px solid #1e293b; font-size: 14px; }
        th { color: #3b82f6; font-size: 13px; }
        .btn-ui { padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer; color: white; margin-right: 5px; }
        .status-tag { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: rgba(34, 197, 94, 0.15); color: #4ade80; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>DigiVerify</h2>
        <div class="menu-label">MAIN MENU</div>
        <nav class="sidebar-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-documents.php">Documents</a>
            <a href="verify-documents.php" class="active">Verify Documents</a>
            <a href="manage-users.php">Users</a>
            <a href="notifications.php">Notifications</a>
            <a href="profile.php">Profile</a>
        </nav>
    </aside>
    <main class="main-content">
        <header class="header-panel">
            <div><h1>Verify Documents</h1><p>Approve or Reject Uploaded Documents</p></div>
        </header>
        <section class="verification-panel">
            <table>
                <thead><tr><th>ID</th><th>User ID</th><th>Document</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <tr><td>12</td><td>USR_9912</td><td>AADHAAR CARD</td><td><span class="status-tag">APPROVED</span></td><td><button class="btn-ui" style="background:#22c55e;">Approve</button><button class="btn-ui" style="background:#ef4444;">Reject</button></td></tr>
                    <tr><td>11</td><td>USR_9911</td><td>UNKNOWN DOCUMENT</td><td><span class="status-tag" style="background:rgba(239,68,68,0.15);color:#f87171;">REJECTED</span></td><td><button class="btn-ui" style="background:#22c55e;">Approve</button><button class="btn-ui" style="background:#ef4444;">Reject</button></td></tr>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
