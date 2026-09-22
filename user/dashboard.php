<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🎯 College Presentation Dashboard Bypass (0% Error)
$_SESSION['user_id'] = '1';
$_SESSION['user_name'] = 'User';
$_SESSION['user_email'] = 'riddhi@gmail.com';

error_reporting(0);
ini_set('display_errors', 0);

$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];

$doc_type_display = 'AADHAAR CARD (UIDAI)';
$status_display = 'APPROVED';
$ai_confidence = "94%";
$fraud_score = "6%";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify User Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 30px; text-align: center; }
        .dashboard-header { margin-bottom: 40px; }
        .dashboard-header h1 { font-size: 32px; font-weight: bold; margin: 0; display: flex; align-items: center; justify-content: center; gap: 15px; }
        .welcome-text { color: #00d2ff; font-size: 18px; margin-top: 10px; font-weight: 600; }
        .user-email { color: #64748b; font-size: 14px; margin-top: 2px; }
        .stats-wrapper { display: flex; justify-content: center; gap: 20px; max-width: 1200px; margin: 0 auto 40px auto; flex-wrap: wrap; }
        .stat-box { background: rgba(11, 21, 40, 0.6); border: 1px solid #102a45; border-radius: 12px; padding: 25px; width: 220px; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }
        .stat-box h2 { font-size: 24px; margin: 0 0 5px 0; font-weight: bold; }
        .stat-box p { font-size: 12px; color: #52789c; text-transform: uppercase; margin: 0; font-weight: bold; letter-spacing: 0.5px; }
        .color-blue { color: #00d2ff; }
        .color-green { color: #10b981; }
        .grid-table-container { max-width: 1200px; margin: 0 auto 40px auto; overflow-x: auto; background: rgba(11, 21, 40, 0.4); border-radius: 12px; border: 1px solid #102a45; max-height: 400px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: linear-gradient(90deg, #1d4ed8, #0ea5e9); color: #fff; font-weight: bold; padding: 16px; font-size: 14px; position: sticky; top: 0; }
        td { padding: 16px; border-bottom: 1px solid #102a45; font-size: 14px; color: #cbd5e1; }
        tr:hover { background: rgba(16, 42, 69, 0.3); }
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-approved { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .footer-actions { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 15px; font-weight: bold; text-decoration: none; border: none; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.2s; }
        .btn-digital-id { background: #2563eb; color: #fff; }
        .btn-digital-id:hover { background: #1d4ed8; }
        .btn-logout { background: #1e1b29; color: #ef4444; border: 1px solid #3b1820; }
        .btn-download { background: rgba(14, 165, 233, 0.2); color: #38bdf8; border: 1px solid #0ea5e9; padding: 4px 10px; border-radius: 4px; font-size: 12px; text-decoration: none; display: inline-block; width: 110px; text-align: center; font-weight: 600; cursor: pointer; }
        .btn-download:hover { background: #0ea5e9; color: #fff; }
    </style>
</head>
<body>
    <!-- DASHBOARD HEADER -->
    <div class="dashboard-header">
        <h1>DigiVerify User Dashboard</h1>
        <div class="welcome-text">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        <div class="user-email"><?php echo htmlspecialchars($user_email); ?></div>
    </div>

    <!-- STATS COUNTERS ROW -->
    <div class="stats-wrapper">
        <div class="stat-box">
            <h2 class="color-blue" style="font-size: 18px; word-break: break-all;"><?php echo $doc_type_display; ?></h2>
            <p>Latest Document</p>
        </div>
        <div class="stat-box">
            <h2 class="color-blue"><?php echo $status_display; ?></h2>
            <p>Status</p>
        </div>
        <div class="stat-box">
            <h2 class="color-green"><?php echo $ai_confidence; ?></h2>
            <p>AI Confidence</p>
        </div>
        <div class="stat-box">
            <h2 class="color-blue"><?php echo $fraud_score; ?></h2>
            <p>Fraud Score</p>
        </div>
    </div>

    <!-- DOCUMENT RECORDS GRID TABLE START -->
    <div class="grid-table-container">
        <table>
            <thead>
                <tr>
                    <th>Reference ID</th>
                    <th>Document</th>
                    <th>AI Score</th>
                    <th>Fraud Score</th>
                    <th>Status</th>
                    <th>Recommendation</th>
                    <th>Date</th>
                    <th>Certificate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>REF-5098</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5012</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5011</td>
                    <td>UNKNOWN DOCUMENT</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-rejected">REJECTED</span></td>
                    <td>Invalid Document</td>
                    <td>2026-08-12</td>
                    <td><span style="color: #64748b; font-size:12px;">Locked</span></td>
                </tr>
                <tr>
                    <td>REF-5010</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5009</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5008</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5007</td>
                    <td>UNKNOWN DOCUMENT</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-rejected">REJECTED</span></td>
                    <td>Invalid Document</td>
                    <td>2026-08-12</td>
                    <td><span style="color: #64748b; font-size:12px;">Locked</span></td>
                </tr>
                <tr>
                    <td>REF-5006</td>
                    <td>UNKNOWN DOCUMENT</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-rejected">REJECTED</span></td>
                    <td>Invalid Document</td>
                    <td>2026-08-12</td>
                    <td><span style="color: #64748b; font-size:12px;">Locked</span></td>
                </tr>
                <tr>
                    <td>REF-5005</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5004</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
                <tr>
                    <td>REF-5003</td>
                    <td>UNKNOWN DOCUMENT</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-rejected">REJECTED</span></td>
                    <td>Invalid Document</td>
                    <td>2026-08-12</td>
                    <td><span style="color: #64748b; font-size:12px;">Locked</span></td>
                </tr>
                <tr>
                    <td>REF-5002</td>
                    <td>AADHAAR CARD (UIDAI)</td>
                    <td>94%</td>
                    <td>6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-12</td>
                    <td><a onclick="downloadCertificate()" class="btn-download">Get Certificate</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FOOTER ACTIONS BUTTONS -->
    <div class="footer-actions">
        <a href="digital-id.php" class="btn btn-digital-id">Digital ID</a>
        <a href="login.php" class="btn btn-logout">Logout</a>
    </div>

    <!-- Magic Hidden Script to Download Certificate directly without changing page -->
    <script>
    function downloadCertificate() {
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = "../verify/verification_result.php?id=98";
        document.body.appendChild(iframe);
        
        iframe.onload = function() {
            iframe.contentWindow.print();
            setTimeout(function() {
                document.body.removeChild(iframe);
            }, 1000);
        };
    }
    </script>

</body>
</html>
