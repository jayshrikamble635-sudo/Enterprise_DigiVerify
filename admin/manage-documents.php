<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../database/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// डेटाबेस से सभी डाक्यूमेंट्स की लिस्ट निकालना
$sql = "SELECT d.*, u.fullname FROM documents d
        LEFT JOIN users u ON d.user_id = u.id 
        ORDER BY d.id DESC";
$all_documents = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Documents | DigiVerify Admin</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #0b0f19; color: #f8fafc; display: flex; min-height: 100vh; overflow-x: hidden; }

        /* LEFT SIDEBAR DESIGN */
        .sidebar {
            width: 260px; background: rgba(15, 23, 42, 0.9); border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px 20px; display: flex; flex-direction: column; backdrop-filter: blur(10px);
            position: fixed; height: 100vh; z-index: 100; left: 0; top: 0;
        }
        .sidebar-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
        .sidebar-logo h2 { font-size: 22px; font-weight: 800; color: #ffffff; }
        .sidebar-logo p { font-size: 11px; color: #38bdf8; text-transform: uppercase; font-weight: 600; margin-top: 2px; }
        .menu-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; margin-bottom: 15px; }
        .menu-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .menu-item a {
            display: flex; align-items: center; gap: 14px; color: #cbd5e1; text-decoration: none;
            padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 500; transition: 0.2s;
        }
        .menu-item.active a, .menu-item a:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(6, 182, 212, 0.05));
            color: #38bdf8; border-left: 3px solid #2563eb; padding-left: 13px;
        }
        .sidebar-footer { margin-top: auto; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px; }
        .sidebar-footer p { font-size: 11px; color: #475569; }

        /* RIGHT MAIN CONTENT AREA */
        .main-content {
            margin-left: 260px; flex: 1; width: calc(100% - 260px); padding: 40px;
            position: relative; z-index: 1; display: block; background-color: #0b0f19; min-height: 100vh;
        }
        .top-header {
            background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 25px 30px; border-radius: 20px; display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 30px; backdrop-filter: blur(10px);
        }
        .top-header h1 { font-size: 26px; font-weight: 800; color: #ffffff; }
        .top-header p { color: #94a3b8; font-size: 13.5px; margin-top: 3px; }

        .table-card { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 30px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(255,255,255,0.02); color: #64748b; padding: 14px; font-size: 12px; text-transform: uppercase; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        td { padding: 14px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 14px; color: #cbd5e1; }

        .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status.approved { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .status.rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
        .status.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .dashboard-footer { border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; display: flex; justify-content: space-between; color: #475569; font-size: 13px; }
    </style>
</head>
<body>

    <!-- LEFT SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://w3.org" style="filter: drop-shadow(0 0 8px rgba(37, 99, 235, 0.6));">
                <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#2563eb" stroke="#38bdf8" stroke-width="2" stroke-linejoin="round"/>
                <path d="M12 6V18" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                <path d="M9 11L12 14L15 11" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
                <h2>DigiVerify</h2>
                <p>Admin Edge</p>
            </div>
        </div>

        <p class="menu-label">Main Menu</p>
        <ul class="menu-list">
            <li class="menu-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="menu-item active"><a href="manage-documents.php">Documents</a></li>
            <li class="menu-item"><a href="verify-requests.php">Verify Documents</a></li>
            <li class="menu-item"><a href="manage-users.php">Users</a></li>
            <li class="menu-item"><a href="notifications.php">Notifications</a></li>
            <li class="menu-item"><a href="profile.php">Profile</a></li>
        </ul>

        <div class="sidebar-footer">
            <p>Enterprise DigiVerify</p>
            <p style="color: #38bdf8; font-size: 10px; margin-top: 4px;">Developed by Riddhi Kamble</p>
        </div>
    </div>

    <!-- MAIN RIGHT PANEL -->
    <div class="main-content">
        <div class="top-header">
            <div>
                <h1>Manage Documents</h1>
                <p>Global Repository Document Audit Grid</p>
            </div>
            <div class="admin-profile">
                <span>Administrator</span>
            </div>
        </div>

        <div class="table-card">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Ref ID</th><th>User</th><th>Email</th><th>Document</th><th>AI Score</th><th>Fraud Score</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($all_documents && mysqli_num_rows($all_documents) > 0) {
                            while($row = mysqli_fetch_assoc($all_documents)){ 
                        ?>
                        <tr>
                            <td>DV<?= str_pad($row['id'], 6, "0", STR_PAD_LEFT); ?></td>
                            <td><?= !empty($row['fullname']) ? htmlspecialchars($row['fullname']) : "Registered User"; ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? ''); ?></td>
                            <td><?= strtoupper($row['document_type'] ?? 'UNKNOWN'); ?></td>
                            <td><?= $row['ai_confidence'] ?? 0; ?>%</td>
                            <td><?= $row['fraud_score'] ?? 0; ?>%</td>
                            <td>
                                <?php
                                $status = $row['verification_status'] ?? $row['status'] ?? 'Pending';
                                if($status=="Approved") { echo "<span class='status approved'>Approved</span>"; }
                                elseif($status=="Rejected") { echo "<span class='status rejected'>Rejected</span>"; }
                                else { echo "<span class='status pending'>Pending</span>"; }
                                ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center; color:#64748b;'>No Documents Found inside Global Node.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-footer">
            <p>Enterprise DigiVerify | <span>AI Powered Document Verification System</span></p>
            <p>© <?= date("Y"); ?> All Rights Reserved</p>
        </div>
    </div>

</body>
</html>
