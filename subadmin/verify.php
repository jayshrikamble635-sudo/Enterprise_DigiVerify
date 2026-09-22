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

$mock_verification_pool = [
    ["id" => 109, "holder" => "SUDHIR MANDAL", "document_type" => "PAN CARD (INCOME TAX)", "status" => "PENDING"],
    ["id" => 108, "holder" => "RIDDHI BALAJI KAMBLE", "document_type" => "AADHAAR CARD (UIDAI)", "status" => "APPROVED"],
    ["id" => 107, "holder" => "SNEHA SANJAY PATIL", "document_type" => "AADHAAR CARD (UIDAI)", "status" => "APPROVED"],
    ["id" => 105, "holder" => "UNKNOWN HOLDER", "document_type" => "INVALID DOCUMENT", "status" => "REJECTED"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify Sub-Admin - Verify</title>
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
        .top-badge { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
        .welcome-title { font-size: 28px; font-weight: bold; margin: 0 0 5px 0; }
        .welcome-sub { font-size: 14px; color: #64748b; margin-bottom: 30px; }
        
        .grid-container { background: rgba(11, 21, 40, 0.4); border-radius: 12px; border: 1px solid #102a45; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0b1528; color: #475569; font-weight: bold; padding: 16px; font-size: 12px; text-transform: uppercase; text-align: left; border-bottom: 1px solid #102a45; }
        td { padding: 16px; border-bottom: 1px solid #102a45; font-size: 14px; color: #cbd5e1; text-align: left; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .status-approved { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .status-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        
        .btn-action { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; border: none; margin-right: 5px; }
        .btn-approve { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; }
        .btn-reject { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; }
        .btn-approve:hover { background: #10b981; color: #fff; }
        .btn-reject:hover { background: #ef4444; color: #fff; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-area">DigiVerify</div>
        <div class="logo-sub">SUB-ADMIN CORE</div>
        <div class="menu-title">Main Menu</div>
        <a href="dashboard.php" class="menu-item">Dashboard</a>
        <a href="documents.php" class="menu-item">Documents</a>
        <a href="verify.php" class="menu-item active">Verify Documents</a>
        <a href="notifications.php" class="menu-item">Notifications</a>
        <a href="profile.php" class="menu-item">Profile</a>
        <a href="logout.php" class="menu-item" style="color: #ef4444; border-top: 1px solid #102a45; margin-top: 20px; padding-top: 15px;">Sub-Admin Logout</a>
    </div>

    <div class="main-content">
        <div class="top-badge">Operational Grid</div>
        <div class="welcome-title">Compliance Evaluation Console</div>
        <div class="welcome-sub">Instantly approve or reject submitted node compliance identity files.</div>

        <div class="grid-container">
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Holder Name</th>
                        <th>Document Type</th>
                        <th>Current State</th>
                        <th>Action Protocol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mock_verification_pool as $row) { 
                        $statusClass = 'status-' . strtolower($row['status']);
                    ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td style="color: #38bdf8;">#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['holder']; ?></td>
                        <td><?php echo $row['document_type']; ?></td>
                        <td><span id="badge-<?php echo $row['id']; ?>" class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if ($row['status'] == 'PENDING') { ?>
                                <span id="actions-<?php echo $row['id']; ?>">
                                    <button class="btn-action btn-approve" onclick="process(<?php echo $row['id']; ?>, 'APPROVED')">Approve</button>
                                    <button class="btn-action btn-reject" onclick="process(<?php echo $row['id']; ?>, 'REJECTED')">Reject</button>
                                </span>
                            <?php } else { ?>
                                <span style="color:#64748b; font-size:12px;">🔒 Finalized</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function process(id, type) {
            let el = document.getElementById('badge-'+id);
            el.innerText = type;
            el.className = 'status-badge ' + (type === 'APPROVED' ? 'status-approved' : 'status-rejected');
            document.getElementById('actions-'+id).innerHTML = '<span style="color:#64748b; font-size:12px;">🔒 Processed</span>';
        }
    </script>
</body>
</html>
