<?php
// 1. DATABASE CONNECTION
$host = "localhost";
$username = "root";
$password = "";
$database = "digiverify"; // आपका मुख्य डेटाबेस

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. APPROVE / REJECT ACTION PROCESSING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_action'])) {
    $docId = intval($_POST['doc_id']);
    $actionType = mysqli_real_escape_string($conn, $_POST['action_type']); // 'APPROVED' या 'REJECTED'
    
    // डेटाबेस में स्टेटस अपडेट करने की क्वेरी
    $updateQuery = "UPDATE verified_documents SET status = '$actionType' WHERE id = $docId";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo json_encode(["status" => "success", "message" => "Document successfully " . strtolower($actionType)]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database update failed."]);
    }
    exit;
}

// 3. FETCH ALL DOCUMENTS FOR VERIFICATION PANEL
// 🔴 यहाँ से WHERE शर्त हटा दी गई है ताकि आपके सभी 12 रिकॉर्ड्स टेबल में आ सकें
$records_query = mysqli_query($conn, "SELECT id, document_type, status FROM verified_documents ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Documents Panel | DigiVerify</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        /* डार्क थीम लेआउट स्टाइल्स (मैचिंग स्क्रीनशॉट) */
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #050e1e; color: #e2e8f0; display: flex; }
        .sidebar { width: 260px; background: #0b1528; color: white; min-height: 100vh; padding: 20px; box-sizing: border-box; border-right: 1px solid #1e293b; }
        .sidebar h2 { font-size: 22px; margin: 0 0 5px 0; color: #fff; }
        .menu-label { font-size: 11px; color: #64748b; font-weight: bold; margin: 20px 0 10px 0; letter-spacing: 1px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 10px; color: #94a3b8; padding: 12px; text-decoration: none; border-radius: 6px; margin-bottom: 5px; font-size: 14px; }
        .sidebar-menu a.active, .sidebar-menu a:hover { background: #1e293b; color: #3b82f6; }
        
        .main-content { flex: 1; padding: 40px; box-sizing: border-box; }
        .header-panel { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-panel h1 { margin: 0; font-size: 26px; color: #fff; }
        .header-panel p { margin: 5px 0 0 0; color: #94a3b8; font-size: 14px; }
        
        .panel-badge { background: #0f172a; border: 1px solid #1e293b; padding: 10px 20px; border-radius: 8px; display: flex; align-items: center; gap: 10px; }
        .panel-badge i { color: #3b82f6; }
        .panel-badge div { font-size: 12px; font-weight: bold; }
        
        /* वेरिफिकेशन ग्रिड कार्ड */
        .verification-panel { background: #0b1528; padding: 30px; border-radius: 12px; border: 1px solid #1e293b; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .panel-meta h3 { margin: 0 0 20px 0; color: #fff; font-size: 18px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid #1e293b; font-size: 14px; }
        th { color: #3b82f6; font-weight: 600; font-size: 13px; letter-spacing: 0.5px; }
        td { color: #cbd5e1; }
        
        /* इंटरएक्टिव बटन्स */
        .action-group { display: flex; gap: 8px; }
        .btn-ui { padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-approve { background: #22c55e; color: white; }
        .btn-approve:hover { background: #16a34a; }
        .btn-reject { background: #ef4444; color: white; }
        .btn-reject:hover { background: #dc2626; }
        
        .status-tag { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-APPROVED { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .status-REJECTED { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .status-PENDING { background: rgba(234, 179, 8, 0.15); color: #facc15; }
        
        footer { margin-top: 40px; font-size: 13px; color: #64748b; text-align: center; }
    </style>
</head>
<body>

    <!-- SIDEBAR: MAIN MENU -->
    <aside class="sidebar">
        <h2>DigiVerify</h2>
        <div class="menu-label">SUB ADMIN PANEL</div>
        <div class="menu-label">MAIN MENU</div>
        <nav class="sidebar-menu">
            <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="all-documents.php"><i class="fa-solid fa-folder"></i> Documents</a>
            <a href="verify-documents.php" class="active"><i class="fa-solid fa-file-circle-check"></i> Verify Documents</a>
            <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
            <a href="notifications.php"><i class="fa-solid fa-bell"></i> Notifications</a>
            <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
        <div style="margin-top: 100px; font-size: 11px; color: #475569;">Enterprise DigiVerify<br>Sub Admin Panel</div>
    </aside>
    <!-- MAIN CONTENT MODULE -->
    <main class="main-content">
        <header class="header-panel">
            <div>
                <h1>Verify Documents</h1>
                <p>Approve or Reject Uploaded Documents</p>
            </div>
            <div class="panel-badge">
                <i class="fa-solid fa-circle-check fa-lg"></i>
                <div>Verification Panel<br><span style="color:#64748b;">Enterprise DigiVerify</span></div>
            </div>
        </header>

        <!-- VERIFICATION PANEL CARD -->
        <section class="verification-panel">
            <div class="panel-meta">
                <h3>Document Verification</h3>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($records_query) > 0) {
                        while ($row = mysqli_fetch_assoc($records_query)) {
                            // आपके प्रदान किए गए डेटाबेस स्क्रीनशॉट के अनुसार नकली User ID मैपिंग
                            $mock_userid = "USR_99" . $row['id']; 
                            
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $mock_userid . "</td>";
                            echo "<td>" . strtoupper($row['document_type']) . "</td>";
                            echo "<td><span class='status-tag status-" . $row['status'] . "'>" . $row['status'] . "</span></td>";
                            echo "<td>";
                            
                            // एक्शन बटन ब्लॉक
                            echo "<div class='action-group'>";
                            echo "<button class='btn-ui btn-approve' onclick='submitVerificationDecision(" . $row['id'] . ", \"APPROVED\")'>Approve</button>";
                            echo "<button class='btn-ui btn-reject' onclick='submitVerificationDecision(" . $row['id'] . ", \"REJECTED\")'>Reject</button>";
                            echo "</div>";
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 20px;'>No database logs discovered.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <footer>
            <p>© 2026 Enterprise DigiVerify | Verify Documents | Developed by Riddhi Kamble</p>
        </footer>
    </main>

    <!-- AJAX SCRIPT -->
    <script>
    async function submitVerificationDecision(docId, actionName) {
        if (!confirm(`Are you sure you want to change status to ${actionName}?`)) return;

        const requestBody = new FormData();
        requestBody.append('process_action', '1');
        requestBody.append('doc_id', docId);
        requestBody.append('action_type', actionName);

        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: requestBody
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                window.location.reload(); // स्थिति बदलने के बाद तुरंत पेज रिफ्रेश होगा
            } else {
                alert("Error: " + result.message);
            }
        } catch (error) {
            console.error("Connection error:", error);
            alert("Failed to connect to server.");
        }
    }
    </script>
</body>
</html>
