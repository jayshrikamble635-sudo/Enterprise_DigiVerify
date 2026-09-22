<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['subadmin_id'])){
    header("Location: login.php");
    exit();
}

$total = 0; $pending = 0; $approved = 0; $rejected = 0;

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_logs");
if ($result) { $row = mysqli_fetch_assoc($result); $total = (int)$row['total']; }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_logs WHERE status='PENDING'");
if ($result) { $row = mysqli_fetch_assoc($result); $pending = (int)$row['total']; }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_logs WHERE status='APPROVED'");
if ($result) { $row = mysqli_fetch_assoc($result); $approved = (int)$row['total']; }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_logs WHERE status='REJECTED'");
if ($result) { $row = mysqli_fetch_assoc($result); $rejected = (int)$row['total']; }

$documents = mysqli_query($conn, "SELECT id, document_type, status, created_at AS uploaded_at FROM verification_logs ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Documents | Enterprise DigiVerify</title>
<link rel="stylesheet" href="css/subadmin.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">
<header class="top-header">
    <div class="header-left">
        <h1>All Documents</h1>
        <p>Manage and verify uploaded documents</p>
    </div>
</header>

<section class="hero-section">
    <div class="hero-left">
        <span class="badge"><i class="fas fa-shield-halved"></i> Enterprise DigiVerify</span>
        <h2>Document Verification Control Center</h2>
        <p>Review uploaded identity documents and manage verification logs.</p>
    </div>
    <div class="hero-right">
        <div class="mini-card"><span>Total</span><h3><?php echo $total; ?></h3></div>
        <div class="mini-card"><span>Pending</span><h3><?php echo $pending; ?></h3></div>
        <div class="mini-card"><span>Approved</span><h3><?php echo $approved; ?></h3></div>
        <div class="mini-card"><span>Rejected</span><h3><?php echo $rejected; ?></h3></div>
    </div>
</section>

<div class="table-card">
    <div class="table-header">
        <h3><i class="fas fa-file-lines"></i> Document Records</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr><th>ID</th><th>Document Type</th><th>Status</th><th>Uploaded At</th></tr>
            </thead>
            <tbody>
            <?php
            if ($documents && mysqli_num_rows($documents) > 0) {
                while ($row = mysqli_fetch_assoc($documents)) {
                    $statusClass = strtolower($row['status'] ?? 'pending');
            ?>
                <tr>
                    <td><strong><?php echo $row['id']; ?></strong></td>
                    <td><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($row['document_type']); ?></td>
                    <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No documents found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</body>
</html>
