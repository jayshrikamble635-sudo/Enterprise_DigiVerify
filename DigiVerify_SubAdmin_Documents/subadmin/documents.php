<?php
session_start();
require_once __DIR__ . '/../database/config.php';

/* Fetch documents safely. Works with the existing DigiVerify documents table. */
$sql = "SELECT d.*, u.fullname 
        FROM documents d
        LEFT JOIN users u ON d.user_id = u.id
        ORDER BY d.id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    $result = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents | Enterprise DigiVerify</title>
    <link rel="stylesheet" href="css/subadmin.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div>
                <h2>DigiVerify</h2>
                <span>SUB ADMIN PANEL</span>
            </div>
        </div>

        <div class="menu-title">MAIN MENU</div>

        <a href="dashboard.php" class="menu-item">
            <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
        </a>

        <a href="documents.php" class="menu-item active">
            <i class="fa-solid fa-file-lines"></i><span>Documents</span>
        </a>

        <a href="verify.php" class="menu-item">
            <i class="fa-solid fa-circle-check"></i><span>Verify Documents</span>
        </a>

        <a href="users.php" class="menu-item">
            <i class="fa-solid fa-users"></i><span>Users</span>
        </a>

        <a href="notifications.php" class="menu-item">
            <i class="fa-solid fa-bell"></i><span>Notifications</span>
        </a>

        <a href="profile.php" class="menu-item">
            <i class="fa-solid fa-user"></i><span>Profile</span>
        </a>

        <a href="../logout.php" class="menu-item logout">
            <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
        </a>
    </aside>

    <main class="content">

        <header class="topbar">
            <div>
                <h1>Documents</h1>
                <p>Manage and monitor all uploaded verification documents</p>
            </div>
            <div class="top-actions">
                <div class="top-icon"><i class="fa-solid fa-bell"></i></div>
                <div class="admin-badge">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Sub Admin</span>
                </div>
            </div>
        </header>

        <section class="hero">
            <div>
                <span class="hero-tag">ENTERPRISE DIGIVERIFY</span>
                <h2>All Verification Documents</h2>
                <p>Review uploaded identity documents, verification status and AI analysis.</p>
            </div>
            <div class="hero-icon">
                <i class="fa-solid fa-folder-open"></i>
            </div>
        </section>

        <section class="stats">
            <?php
            $total = $approved = $rejected = $pending = 0;
            if ($result) {
                mysqli_data_seek($result, 0);
                while ($r = mysqli_fetch_assoc($result)) {
                    $total++;
                    $status = strtolower($r['status'] ?? 'pending');
                    if ($status === 'approved') $approved++;
                    elseif ($status === 'rejected') $rejected++;
                    else $pending++;
                }
                mysqli_data_seek($result, 0);
            }
            ?>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-file"></i></div>
                <div><span>Total Documents</span><strong><?= $total ?></strong></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div><span>Approved</span><strong><?= $approved ?></strong></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
                <div><span>Rejected</span><strong><?= $rejected ?></strong></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
                <div><span>Pending</span><strong><?= $pending ?></strong></div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>All Documents</h2>
                    <p>Recently uploaded documents</p>
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search documents...">
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>AI Confidence</th>
                        <th>Fraud Score</th>
                        <th>Uploaded</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $status = strtolower($row['status'] ?? 'pending');
                            $statusClass = $status === 'approved' ? 'approved' :
                                           ($status === 'rejected' ? 'rejected' : 'pending');

                            $confidence = $row['ai_confidence'] ?? 0;
                            $fraud = $row['fraud_score'] ?? 0;
                            $uploaded = $row['uploaded_at'] ?? ($row['created_at'] ?? '-');
                            ?>
                            <tr>
                                <td><b>#<?= htmlspecialchars($row['id'] ?? '') ?></b></td>
                                <td><?= htmlspecialchars($row['fullname'] ?? ('User ' . ($row['user_id'] ?? '-'))) ?></td>
                                <td>
                                    <div class="doc-name">
                                        <span class="doc-icon"><i class="fa-solid fa-file-lines"></i></span>
                                        <?= htmlspecialchars($row['document_type'] ?? 'Document') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status <?= $statusClass ?>">
                                        <?= ucfirst(htmlspecialchars($status)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="score">
                                        <span><?= htmlspecialchars($confidence) ?>%</span>
                                        <div class="bar"><i style="width:<?= min(100, max(0, (float)$confidence)) ?>%"></i></div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($fraud) ?>%</td>
                                <td><?= htmlspecialchars($uploaded) ?></td>
                                <td>
                                    <a class="view-btn" href="verify.php?id=<?= urlencode($row['id'] ?? '') ?>">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty">
                                <i class="fa-solid fa-folder-open"></i>
                                <h3>No Documents Found</h3>
                                <p>Uploaded documents will appear here.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

</body>
</html>
