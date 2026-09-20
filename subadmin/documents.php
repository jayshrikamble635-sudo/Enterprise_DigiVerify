<?php

include("../database/config.php");

/* ================= COUNTS ================= */

$total = 0;
$pending = 0;
$approved = 0;
$rejected = 0;
$today = 0;

/* TOTAL */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM verification_logs");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total = (int)$row['total'];
}

/* PENDING */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM verification_logs WHERE status='PENDING'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending = (int)$row['total'];
}

/* APPROVED */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM verification_logs WHERE status='APPROVED'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $approved = (int)$row['total'];
}

/* REJECTED */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM verification_logs WHERE status='REJECTED'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $rejected = (int)$row['total'];
}

/* TODAY */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM verification_logs
     WHERE DATE(created_at)=CURDATE()"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $today = (int)$row['total'];
}

/* VERIFICATION RATE */
$verificationRate = 0;

if ($total > 0) {
    $verificationRate = round(($approved / $total) * 100);
}

/* ================= DOCUMENTS ================= */

$documents = mysqli_query(
    $conn,
    "SELECT id, document_type, status, created_at AS uploaded_at FROM verification_logs ORDER BY id DESC"
);

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Documents | Enterprise DigiVerify</title>

<!-- SUB ADMIN / ADMIN STYLE -->
<link rel="stylesheet"
      href="css/subadmin.css?v=<?php echo time(); ?>">

<!-- FONT AWESOME -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
}

.main-content {
    min-height: 100vh;
}

.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 35px;
}

.header-left h1 {
    margin: 0;
}

.header-left p {
    margin-top: 8px;
}

.admin-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 25px;
}

.logout-btn {
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 10px;
}

.hero-section {
    margin: 20px 35px;
    padding: 35px;
    border-radius: 20px;
    display: flex;
    justify-content: space-between;
    gap: 30px;
}

.hero-left {
    max-width: 650px;
}

.hero-left h2 {
    font-size: 30px;
    margin: 20px 0 15px;
}

.hero-left p {
    line-height: 1.7;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 15px;
    border-radius: 20px;
}

.hero-right {
    display: grid;
    grid-template-columns: repeat(2, 150px);
    gap: 15px;
}

.mini-card {
    padding: 20px;
    border-radius: 15px;
}

.mini-card span {
    font-size: 13px;
}

.mini-card h3 {
    margin: 10px 0 0;
    font-size: 25px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(180px, 1fr));
    gap: 20px;
    padding: 10px 35px 30px;
}

.card {
    position: relative;
    padding: 25px;
    border-radius: 18px;
    min-height: 135px;
}

.card span {
    font-size: 14px;
}

.card h2 {
    font-size: 32px;
    margin: 15px 0;
}

.card i {
    position: absolute;
    right: 22px;
    bottom: 20px;
    font-size: 30px;
}

.table-card {
    margin: 0 35px 25px;
    padding: 25px;
    border-radius: 20px;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.table-header h3 {
    margin: 0;
}

.table-header p {
    margin-top: 7px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 15px;
    text-align: left;
}

.status {
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 13px;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
}

@media(max-width:900px) {
    .top-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    .header-right { width: 100%; justify-content: space-between; }
    .hero-section { flex-direction: column; }
    .hero-right { width: 100%; grid-template-columns: repeat(2, 1fr); }
    .card-grid { grid-template-columns: repeat(2, 1fr); }
}

@media(max-width:600px) {
    .top-header { padding: 20px; }
    .hero-section { margin: 15px; padding: 25px; }
    .card-grid { grid-template-columns: 1fr; padding: 10px 15px; }
    .table-card { margin: 0 15px 20px; padding: 18px; }
    .header-right { flex-direction: column; align-items: flex-start; }
}
</style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<?php include("includes/sidebar.php"); ?>

<!-- ================= MAIN ================= -->
<div class="main-content">

<!-- ================= HEADER ================= -->
<header class="top-header">
    <div class="header-left">
        <h1>All Documents</h1>
        <p>Manage and verify uploaded documents</p>
    </div>

    <div class="header-right">
        <div class="admin-profile">
            <div class="profile-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h4>Sub Administrator</h4>
                <span>Enterprise DigiVerify</span>
            </div>
        </div>
        <a href="logout.php" class="logout-btn"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero-section">
    <div class="hero-left">
        <span class="badge"><i class="fas fa-shield-halved"></i> Enterprise DigiVerify</span>
        <h2>Document Verification Control Center</h2>
        <p>Review uploaded identity documents, monitor verification status and securely manage all documents from the Sub Admin control center.</p>
    </div>

    <div class="hero-right">
        <div class="mini-card">
            <span>Total Documents</span>
            <h3><?php echo $total; ?></h3>
        </div>
        <div class="mini-card">
            <span>Pending</span>
            <h3><?php echo $pending; ?></h3>
        </div>
        <div class="mini-card">
            <span>Approved</span>
            <h3><?php echo $approved; ?></h3>
        </div>
        <div class="mini-card">
            <span>Rejected</span>
            <h3><?php echo $rejected; ?></h3>
        </div>
    </div>
</section>

<!-- ================= STAT CARDS ================= -->
<div class="card-grid">
    <div class="card glow-card">
        <span>Total Documents</span>
        <h2><?php echo $total; ?></h2>
        <i class="fas fa-file-alt"></i>
    </div>
    <div class="card">
        <span>Pending Verification</span>
        <h2><?php echo $pending; ?></h2>
        <i class="fas fa-hourglass-half"></i>
    </div>
    <div class="card">
        <span>Approved Documents</span>
        <h2><?php echo $approved; ?></h2>
        <i class="fas fa-circle-check"></i>
    </div>
</div>

<!-- ================= TABLE ================= -->
<div class="table-card">
    <div class="table-header">
        <div>
            <h3><i class="fas fa-file-lines"></i> Document Records</h3>
            <p>Full sync database verification logs</p>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($documents && mysqli_num_rows($documents) > 0) {
                while ($row = mysqli_fetch_assoc($documents)) {
                    $status = $row['status'] ?? 'PENDING';
                    $statusClass = strtolower($status);
            ?>
                <tr>
                    <td><strong><?php echo $row['id']; ?></strong></td>
                    <td><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($row['document_type']); ?></td>
                    <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                    <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No documents found in database</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
