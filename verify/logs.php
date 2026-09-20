<?php
session_start();

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digiverify_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// === FATAL ERROR FIX ===
$check_status = $conn->query("SHOW COLUMNS FROM `verifications` LIKE 'status'");
if ($check_status->num_rows == 0) {
    $conn->query("ALTER TABLE verifications ADD COLUMN status VARCHAR(50) DEFAULT 'PENDING'");
}

$check_role = $conn->query("SHOW COLUMNS FROM `verifications` LIKE 'verified_by_role'");
if ($check_role->num_rows == 0) {
    $conn->query("ALTER TABLE verifications ADD COLUMN verified_by_role VARCHAR(50) DEFAULT 'User'");
}

// Logged-in user role
$user_role = $_SESSION['user_role'] ?? 'Admin'; 

// Search aur Filter Inputs receive karna
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? mysqli_real_escape_string($conn, $_GET['status_filter']) : '';

// Base SQL Query setup karna
if ($user_role === 'Admin') {
    $sql = "SELECT id, name, document_type, status, verified_by_role FROM verifications WHERE 1=1";
} else {
    $sql = "SELECT id, name, document_type, status, verified_by_role FROM verifications WHERE verified_by_role = '$user_role'";
}

// Search filter apply karna (Name ya ID ke liye)
if (!empty($search_query)) {
    $sql .= " AND (name LIKE '%$search_query%' OR id LIKE '%$search_query%')";
}

// Status dropdown filter apply karna
if (!empty($status_filter)) {
    $sql .= " AND status = '$status_filter'";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Logs | DigiVerify</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0b0f19; color: #ffffff; margin: 0; padding: 40px; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h2 { margin: 0; color: #ffffff; }
        .role-badge { background-color: #5856d6; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        
        /* Search Bar UI */
        .filter-section { display: flex; gap: 15px; margin-bottom: 25px; background: #131926; padding: 15px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .search-input { flex: 1; background: #1a2233; border: 1px solid #1e2638; color: #fff; padding: 12px 15px; border-radius: 6px; font-size: 14px; }
        .filter-select { background: #1a2233; border: 1px solid #1e2638; color: #fff; padding: 12px 15px; border-radius: 6px; font-size: 14px; width: 180px; }
        .btn-search { background: #5856d6; color: #fff; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; transition: 0.3s; }
        .btn-search:hover { background: #4543b3; }
        .btn-reset { background: transparent; color: #8a99ad; border: 1px solid #1e2638; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; line-height: 18px; text-align: center; }

        table { width: 100%; border-collapse: collapse; background: #131926; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #1e2638; }
        th { background-color: #1a2233; color: #8a99ad; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        tr:hover { background-color: #171f30; }
        .status { font-weight: bold; padding: 4px 10px; border-radius: 4px; font-size: 13px; text-transform: uppercase; }
        .status-approved { background-color: rgba(40, 167, 69, 0.2); color: #28a745; }
        .status-rejected { background-color: rgba(220, 53, 69, 0.2); color: #dc3545; }
        .status-pending { background-color: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .btn-back { background: transparent; color: #ffffff; border: 2px solid #5856d6; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; transition: 0.3s; }
        .btn-back:hover { background: #5856d6; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h2>Verification Logs & Records</h2>
            <p style="color: #8a99ad; margin: 5px 0 0 0;">Real-time sync panel for system tracking</p>
        </div>
        <div>
            <span class="role-badge">Logged in as: <?php echo htmlspecialchars($user_role); ?></span>
        </div>
    </div>

    <!-- GET Method Form for Realtime Search -->
    <form method="GET" action="" class="filter-section">
        <input type="text" name="search" placeholder="Search by Holder Name or ID..." class="search-input" value="<?php echo htmlspecialchars($search_query); ?>">
        
        <select name="status_filter" class="filter-select">
            <option value="">All Status</option>
            <option value="APPROVED" <?php if($status_filter === 'APPROVED') echo 'selected'; ?>>Approved</option>
            <option value="REJECTED" <?php if($status_filter === 'REJECTED') echo 'selected'; ?>>Rejected</option>
            <option value="PENDING" <?php if($status_filter === 'PENDING') echo 'selected'; ?>>Pending</option>
        </select>

        <button type="submit" class="btn-search">Apply Filters</button>
        <?php if(!empty($search_query) || !empty($status_filter)): ?>
            <a href="logs.php" class="btn-reset">Reset</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Holder Name</th>
                <th>Document Type</th>
                <th>Verified By</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['document_type']); ?></td>
                        <td><span style="color: #5856d6; font-weight: 600;"><?php echo htmlspecialchars($row['verified_by_role'] ?? 'User'); ?></span></td>
                        <td>
                            <?php 
                            $status_val = strtoupper($row['status'] ?? 'PENDING');
                            $status_class = 'status-pending';
                            if ($status_val === 'APPROVED') $status_class = 'status-approved';
                            if ($status_val === 'REJECTED') $status_class = 'status-rejected';
                            ?>
                            <span class="status <?php echo $status_class; ?>"><?php echo htmlspecialchars($status_val); ?></span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #8a99ad; padding: 30px;">No verification records match your search.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <a href="/Enterprise_DigiVerify/" class="btn-back">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
