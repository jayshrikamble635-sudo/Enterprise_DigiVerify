<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../database/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ऐक्शन लॉजिक: अगर एडमिन 'Approve' या 'Reject' बटन पर क्लिक करता है
if (isset($_GET['action']) && isset($_GET['id'])) {
    $request_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'approve') {
        $status = 'Approved';
        $remarks = 'APPROVED: Document verified and authenticated successfully by Administrator.';
    } elseif ($action == 'reject') {
        $status = 'Rejected';
        $remarks = 'REJECTED: Manual audit failed. Document marked invalid by Administrator.';
    }
    
    if (isset($status)) {
        // documents टेबल के संभावित दोनों स्टेटस कॉलम्स को अपडेट करना
        $update_query = "UPDATE documents SET 
                            status = '$status', 
                            verification_status = '$status',
                            remarks = '$remarks',
                            remark = '$remarks'
                         WHERE id = '$request_id'";
        mysqli_query($conn, $update_query);
        
        header("Location: verify-requests.php?msg=Status Updated Successfully");
        exit();
    }
}

// डेटाबेस से सिर्फ़ 'Pending' डाक्यूमेंट्स की लिस्ट निकालना
$sql = "SELECT d.*, u.fullname FROM documents d
        LEFT JOIN users u ON d.user_id = u.id 
        WHERE d.verification_status='Pending' OR d.status='Pending'
        ORDER BY d.id DESC";
$pending_documents = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Requests | DigiVerify Admin</title>
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

        .alert-msg { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; font-weight: 600; }
        .table-card { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 30px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(255,255,255,0.02); color: #64748b; padding: 14px; font-size: 12px; text-transform: uppercase; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        td { padding: 14px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 14px; color: #cbd5e1; }

        .status-badge { background: rgba(245, 158, 11, 0.15); color: #f59e0b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }

        .action-link { text-decoration: none; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; margin-right: 5px; transition: 0.2s; }
        .btn-approve { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
        .btn-approve:hover { background: #10b981; color: #0b0f19; }
        .btn-reject { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .btn-reject:hover { background: #ef4444; color: white; }

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
            <li class="menu-item"><a href="manage-documents.php">Documents</a></li>
            <li class="menu-item active"><a href="verify-requests.php">Verify Documents</a></li>
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
                <h1>Verify Documents</h1>
                <p>Pending Compliance Approvals Queue</p>
            </div>
            <div class="admin-profile">
                <span>Administrator</span>
            </div>
        </div>

        <?php if(isset($_GET['msg'])){ ?>
            <div class="alert-msg">
                ✔ <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php } ?>

        <div class="table-card">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Ref ID</th><th>User Name</th><th>Email Address</th><th>Document Type</th><th>Fraud Score</th><th>AI Confidence</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($pending_documents && mysqli_num_rows($pending_documents) > 0) {
                            while($row = mysqli_fetch_assoc($pending_documents)){ 
                        ?>
                        <tr>
                            <td>DV<?= str_pad($row['id'], 6, "0", STR_PAD_LEFT); ?></td>
                            <td><?= !empty($row['fullname']) ? htmlspecialchars($row['fullname']) : "Registered User"; ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo strtoupper($row['document_type']); ?></td>
                            <td><?php echo $row['fraud_score']; ?>%</td>
                            <td><?php echo $row['ai_confidence']; ?>%</td>
                            <td><span class="status-badge">Pending</span></td>
                            <td>
                                <a href="verify-requests.php?action=approve&id=<?php echo $row['id']; ?>" class="action-link btn-approve" onclick="return confirm('क्या आप इस दस्तावेज़ को अप्रूव करना चाहते हैं?');">✔ Approve</a>
                                <a href="verify-requests.php?action=reject&id=<?php echo $row['id']; ?>" class="action-link btn-reject" onclick="return confirm('क्या आप इस दस्तावेज़ को रिजेक्ट करना चाहते हैं?');">✖ Reject</a>
                            </td>
                        </tr>
                        <?php 
                            } // व्हाइल लूप यहाँ बिल्कुल सही बंद हुआ है
                        } else {
                            echo "<tr><td colspan='8' style='text-align:center; color:#64748b;'>No Pending Verification Requests Found. All Nodes Clear!</td></tr>";
                        } // इफ कंडीशन यहाँ बिल्कुल सही बंद हुई है
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
