<?php

include("../database/config.php");

/* ================= DOCUMENT COUNTS ================= */

$total = 0;
$pending = 0;
$approved = 0;
$rejected = 0;
$today = 0;

/* TOTAL */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM verification_logs"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
}

/* PENDING */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM verification_logs
     WHERE status='PENDING'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending = $row['total'];
}

/* APPROVED */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM verification_logs
     WHERE status='APPROVED'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $approved = $row['total'];
}

/* REJECTED */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM verification_logs
     WHERE status='REJECTED'"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $rejected = $row['total'];
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
    $today = $row['total'];
}

/* ================= RECENT DOCUMENTS ================= */

$recent = mysqli_query(
    $conn,
    "SELECT id, document_type, status, created_at AS uploaded_at
     FROM verification_logs
     ORDER BY id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Sub Admin Dashboard | Enterprise DigiVerify</title>

    <!-- SUB ADMIN CSS -->
    <link rel="stylesheet"
          href="./css/subadmin.css?v=10">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- ================= SIDEBAR ================= -->

<?php include("includes/sidebar.php"); ?>


<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">


    <!-- ================= HEADER ================= -->

    <header class="top-header">

        <div class="header-left">

            <h1>Sub Admin Dashboard</h1>
        <div class="header-left">
            <p>
                Welcome back,
                <strong>Sub Administrator</strong>
            </p>
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

            <a href="logout.php" class="logout-btn">
                <i class="fas fa-right-from-bracket"></i>
                Logout
            </a>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section class="hero-section">
        <div class="hero-left">
            <span class="badge">
                <i class="fas fa-shield-halved"></i>
                Enterprise DigiVerify
            </span>
            <h2>Welcome to the Sub Admin Control Center</h2>
            <p>
                Review uploaded documents, verify pending requests,
                monitor document status, manage users and keep the
                DigiVerify platform secure from one centralized dashboard.
            </p>
        </div>

        <div class="hero-right">
            <div class="mini-card">
                <span>Pending Reviews</span>
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
            <div class="mini-card">
                <span>Today's Uploads</span>
                <h3><?php echo $today; ?></h3>
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

        <div class="card">
            <span>Rejected Documents</span>
            <h2><?php echo $rejected; ?></h2>
            <i class="fas fa-circle-xmark"></i>
        </div>

        <div class="card">
            <span>Today's Uploads</span>
            <h2><?php echo $today; ?></h2>
            <i class="fas fa-upload"></i>
        </div>

        <div class="card">
            <span>Verification Rate</span>
            <h2>
                <?php
                if ($total > 0) {
                    echo round(($approved / $total) * 100);
                } else {
                    echo "0";
                }
                ?>%
            </h2>
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <!-- ================= RECENT DOCUMENTS ================= -->
    <div class="table-card">
        <div class="table-header">
            <div>
                <h3>
                    <i class="fas fa-clock"></i>
                    Recent Documents
                </h3>
                <p>Latest uploaded documents</p>
            </div>
            <a href="documents.php" class="view-btn">
                <i class="fas fa-file-lines"></i>
                View All Documents
            </a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($recent && mysqli_num_rows($recent) > 0) {
                    while ($row = mysqli_fetch_assoc($recent)) {
                ?>
                    <tr>
                        <td><strong><?php echo $row['id']; ?></strong></td>
                        <td>
                            <i class="fas fa-id-card"></i>
                            <?php echo htmlspecialchars($row['document_type'] ?? 'N/A'); ?>
                        </td>
                        <td>
                            <?php
                            $status = $row['status'] ?? 'PENDING';
                            $statusClass = strtolower($status);
                            ?>
                            <span class="status <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['uploaded_at']); ?>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>No recent documents found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
