<?php
session_start();
include("../database/config.php");

// यदि सब-एडमिन लॉग इन नहीं है तो उसे वापस भेजें
if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}

// 🔴 बिना पेज बदले नोटिफिकेशन एक्शन हैंडल करना (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_action'])) {
    $notifId = intval($_POST['notif_id']);
    $actionType = mysqli_real_escape_string($conn, $_POST['action_type']); // 'READ' या 'DELETE'
    
    if ($actionType === 'READ') {
        $query = "UPDATE notifications SET status = 'READ' WHERE id = $notifId";
    } else {
        $query = "DELETE FROM notifications WHERE id = $notifId";
    }
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Action completed successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database execution failed."]);
    }
    exit;
}

// डेटाबेस की 'notifications' टेबल से अलर्ट्स निकालना
$result = mysqli_query($conn, "SELECT id, message, status, created_at FROM notifications ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Notifications | DigiVerify Sub Admin</title>
    <link rel="stylesheet" href="../admin/css/admin.css">
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        /* नोटिफिकेशन्स के स्टेटस बैज */
        .status-unread { background: rgba(234, 179, 8, 0.15); color: #facc15; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-read { background: rgba(100, 116, 139, 0.15); color: #94a3b8; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .action-group { display: flex; gap: 8px; }
        .btn-ui { padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-read { background: #3b82f6; color: white; }
        .btn-read:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
    </style>
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">
    <div class="top-header">
        <div class="header-left">
            <h1>Sub Admin Panel</h1>
            <p>Notifications &bull; System Alerts & Updates</p>
        </div>
        <div class="header-right">
            <div class="admin-profile">
                <div class="profile-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h4>Notifications</h4>
                    <span>Enterprise DigiVerify</span>
                </div>
            </div>
        </div>
    </div>
    <div class="table-card">
        <div class="table-header">
            <h3>System Notifications</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)){
                        $status_label = strtoupper($row['status']) == 'UNREAD' ? 'UNREAD' : 'READ';
                        $status_class = strtolower($status_label);
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><i class="fa-solid fa-circle-exclamation" style="color: #3b82f6;"></i> <?php echo $row['message']; ?></td>
                    <td>
                        <span class="status-<?php echo $status_class; ?>">
                            <?php echo $status_label; ?>
                        </span>
                    </td>
                    <td><?php echo date("Y-m-d H:i", strtotime($row['created_at'])); ?></td>
                    <td>
                        <div class="action-group">
                            <?php if($status_label == 'UNREAD'){ ?>
                                <button class="btn-ui btn-read" onclick="handleNotification(<?php echo $row['id']; ?>, 'READ')">
                                    <i class="fas fa-eye"></i> Mark Read
                                </button>
                            <?php } ?>
                            <button class="btn-ui btn-delete" onclick="handleNotification(<?php echo $row['id']; ?>, 'DELETE')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:#64748b;'>🔔 No notifications found. System is completely quiet!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-footer">
        <p>
            © <?php echo date("Y"); ?> Enterprise DigiVerify | Notifications Panel | Developed by Riddhi Kamble
        </p>
    </div>
</div>

<!-- लाइव अपडेट्स के लिए AJAX स्क्रिप्ट -->
<script>
async function handleNotification(notifId, actionType) {
    if (actionType === 'DELETE' && !confirm("Are you sure you want to delete this notification?")) return;

    const requestBody = new FormData();
    requestBody.append('notification_action', '1');
    requestBody.append('notif_id', notifId);
    requestBody.append('action_type', actionType);

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: requestBody
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            window.location.reload(); // एक्शन पूरा होते ही ग्रिड तुरंत अपडेट होगा
        } else {
            alert("Database Error: " + result.message);
        }
    } catch (error) {
        console.error("AJAX error:", error);
        alert("Could not update notification state.");
    }
}
</script>

</body>
</html>
