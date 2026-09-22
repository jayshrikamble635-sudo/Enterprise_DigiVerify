<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}
error_reporting(0);
ini_set('display_errors', 0);

$mock_notifications = [
    ["id" => 5, "message" => "Security Node Alert: Document ID #105 failed compliance evaluation.", "status" => "UNREAD", "created_at" => "2026-09-22 18:22:10"],
    ["id" => 4, "message" => "Database Synced: 5 files verified under global node rules.", "status" => "READ", "created_at" => "2026-09-22 14:10:02"],
    ["id" => 3, "message" => "New Upload: AADHAAR CARD assigned to sub-admin allocation list.", "status" => "READ", "created_at" => "2026-09-21 09:30:45"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify Sub-Admin - Notifications</title>
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
        .top-badge { background: rgba(245, 158, 11, 0.1); border: 1px solid #f59e0b; color: #f59e0b; font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
        .welcome-title { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
        .welcome-sub { font-size: 14px; color: #64748b; margin-bottom: 30px; }
        
        .grid-container { background: rgba(11, 21, 40, 0.4); border-radius: 12px; border: 1px solid #102a45; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0b1528; color: #475569; font-weight: bold; padding: 16px; font-size: 12px; text-transform: uppercase; text-align: left; border-bottom: 1px solid #102a45; }
        td { padding: 16px; border-bottom: 1px solid #102a45; font-size: 14px; color: #cbd5e1; text-align: left; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .status-unread { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .status-read { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }
        .btn-action { background: #1e293b; color: #38bdf8; border: 1px solid #102a45; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; }
        .btn-action:hover { background: #38bdf8; color: #040d1a; }
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
        <a href="notifications.php" class="menu-item active">Notifications</a>
        <a href="profile.php" class="menu-item">Profile</a>
        <a href="logout.php" class="menu-item" style="color: #ef4444; border-top: 1px solid #102a45; margin-top: 20px; padding-top: 15px;">Sub-Admin Logout</a>
    </div>

    <div class="main-content">
        <div class="top-badge">System Logs</div>
        <div class="welcome-title">Broadcast Alerts & Notifications</div>
        <div class="welcome-sub">System critical compliance warnings and audit records.</div>

        <div class="grid-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Alert Message</th>
                        <th>Status</th>
                        <th>Timestamp</th>
                        <th>Control Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mock_notifications as $notif) { 
                        $statusClass = ($notif['status'] == 'UNREAD') ? 'status-unread' : 'status-read';
                    ?>
                    <tr id="row-<?php echo $notif['id']; ?>">
                        <td>#<?php echo $notif['id']; ?></td>
                        <td><span style="color:#f87171;">⚠️</span> <?php echo htmlspecialchars($notif['message']); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($notif['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($notif['created_at']); ?></td>
                        <td>
                            <button class="btn-action" onclick="dismiss(<?php echo $notif['id']; ?>)">Dismiss</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function dismiss(id) {
            document.getElementById('row-'+id).style.opacity = '0.3';
        }
    </script>
</body>
</html>
