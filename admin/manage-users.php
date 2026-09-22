<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🎯 Admin User Management Bypass for College Presentation (0% Error)
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | DigiVerify</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #060f1e; color: #f1f5f9; display: flex; }
        
        /* साइडबार मेनू स्टाइल */
        .sidebar { width: 240px; background: #0b1329; min-height: 100vh; padding: 25px; box-sizing: border-box; border-right: 1px solid #1e293b; text-align: left; }
        .sidebar h2 { font-size: 24px; color: #fff; margin: 0; }
        .sidebar-menu a { display: block; color: #94a3b8; padding: 12px 15px; text-decoration: none; border-radius: 6px; font-size: 14px; margin-bottom: 4px; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #1e293b; color: #3b82f6; font-weight: 600; }
        
        /* मुख्य कंटेंट एरिया */
        .main-content { flex: 1; padding: 40px; box-sizing: border-box; text-align: left; }
        .table-card { background: #0b1329; padding: 30px; border-radius: 12px; border: 1px solid #1e293b; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px; border-bottom: 1px solid #1e293b; font-size: 14px; }
        th { color: #38bdf8; background: #0f172a; text-transform: uppercase; font-size: 13px; font-weight: 600; }
        td { color: #cbd5e1; }
        tr:hover { background: rgba(16, 42, 69, 0.3); }
        
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-approved { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .view-btn { color: #38bdf8; text-decoration: none; font-weight: bold; }
        .view-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <!-- SIDEBAR MAIN MENU -->
    <aside class="sidebar">
        <h2>DigiVerify</h2>
        <div style="color:#38bdf8;font-size:11px;font-weight:bold;margin-bottom:30px;">ADMIN EDGE</div>
        <div class="sidebar-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage-documents.php">Documents</a>
            <a href="verify-documents.php">Verify Documents</a>
            <a href="manage-users.php" class="active">Users</a>
            <a href="notifications.php">Notifications</a>
            <a href="profile.php">Profile</a>
        </div>
    </aside>

    <!-- MAIN CONTROL MODULE -->
    <div class="main-content">
        <h1>👥 User Management</h1>
        <p style="color:#64748b; margin-bottom:30px;">Manage registered cloud tenant accounts</p>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Riddhi Kamble</td>
                        <td>riddhi@gmail.com</td>
                        <td>riddhi_kamble</td>
                        <td><span class="status-badge status-approved">User</span></td>
                        <td>2026-08-12 10:05:42</td>
                        <td><a href="#" class="view-btn">View</a></td>
                    </tr>
                    <tr>
                        <td>Jay Shri Kamble</td>
                        <td>jayshri@gmail.com</td>
                        <td>jayshri_sudo</td>
                        <td><span class="status-badge status-approved">User</span></td>
                        <td>2026-08-11 14:22:15</td>
                        <td><a href="#" class="view-btn">View</a></td>
                    </tr>
                    <tr>
                        <td>Balaji Kamble</td>
                        <td>balaji@gmail.com</td>
                        <td>balaji_admin</td>
                        <td><span class="status-badge status-approved" style="background:rgba(37,99,235,0.15);color:#3b82f6;">Sub Admin</span></td>
                        <td>2026-08-10 11:15:30</td>
                        <td><a href="#" class="view-btn">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
