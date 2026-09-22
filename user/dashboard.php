<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🎯 College Presentation Dashboard Bypass (0% Error)
$_SESSION['user_id'] = '1';
$_SESSION['user_name'] = 'Riddhi Balaji Kamble';
$_SESSION['user_email'] = 'riddhi@gmail.com';

// एरर रिपोर्टिंग बंद ताकि डेटाबेस न होने पर भी पेज क्रैश न हो
error_reporting(0);
ini_set('display_errors', 0);

$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];

// स्टैट्स कार्ड्स के लिए डिफ़ॉल्ट कॉलेज प्रेजेंटेशन वैल्यू
$doc_type_display = 'AADHAAR CARD';
$status_display = 'APPROVED';
$ai_confidence = "98.4%";
$fraud_score = "1.6%";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify User Dashboard</title>
    <style>
        /* यूजर डैशबोर्ड विशेष डार्क ग्रिड थीम स्टाइल */
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 30px; text-align: center; }
        .dashboard-header { margin-bottom: 40px; }
        .dashboard-header h1 { font-size: 32px; font-weight: bold; margin: 0; display: flex; align-items: center; justify-content: center; gap: 15px; }
        .welcome-text { color: #00d2ff; font-size: 18px; margin-top: 10px; font-weight: 600; }
        .user-email { color: #64748b; font-size: 14px; margin-top: 2px; }
        
        /* स्टैट्स विजेट रो */
        .stats-wrapper { display: flex; justify-content: center; gap: 20px; max-width: 1200px; margin: 0 auto 40px auto; flex-wrap: wrap; }
        .stat-box { background: rgba(11, 21, 40, 0.6); border: 1px solid #102a45; border-radius: 12px; padding: 25px; width: 220px; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }
        .stat-box h2 { font-size: 24px; margin: 0 0 5px 0; font-weight: bold; }
        .stat-box p { font-size: 12px; color: #52789c; text-transform: uppercase; margin: 0; font-weight: bold; letter-spacing: 0.5px; }
        
        .color-blue { color: #00d2ff; }
        .color-green { color: #10b981; }
        
        /* ग्रिड टेबल कंटेनर */
        .grid-table-container { max-width: 1200px; margin: 0 auto 40px auto; overflow-x: auto; background: rgba(11, 21, 40, 0.4); border-radius: 12px; border: 1px solid #102a45; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: linear-gradient(90deg, #1d4ed8, #0ea5e9); color: #fff; font-weight: bold; padding: 16px; font-size: 14px; }
        td { padding: 16px; border-bottom: 1px solid #102a45; font-size: 14px; color: #cbd5e1; }
        tr:hover { background: rgba(16, 42, 69, 0.3); }
        
        /* बैज स्टाइल */
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-approved { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        
        /* नीचे के एक्शन बटन्स */
        .footer-actions { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 15px; font-weight: bold; text-decoration: none; border: none; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.2s; }
        .btn-digital-id { background: #2563eb; color: #fff; }
        .btn-digital-id:hover { background: #1d4ed8; }
        .btn-logout { background: #1e1b29; color: #ef4444; border: 1px solid #3b1820; }
        .btn-download { background: rgba(14, 165, 233, 0.2); color: #38bdf8; border: 1px solid #0ea5e9; padding: 4px 10px; border-radius: 4px; font-size: 12px; text-decoration: none; }
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

    <!-- DOCUMENT RECORDS GRID TABLE -->
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
                    <td>AADHAAR CARD</td>
                    <td>98.4%</td>
                    <td>1.6%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-09-22</td>
                    <td><a href="#" class="btn-download">Download</a></td>
                </tr>
                <tr>
                    <td>REF-5097</td>
                    <td>PAN CARD</td>
                    <td>96.2%</td>
                    <td>3.8%</td>
                    <td><span class="badge badge-approved">APPROVED</span></td>
                    <td>Trusted Identity</td>
                    <td>2026-08-15</td>
                    <td><a href="#" class="btn-download">Download</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="footer-actions">
        <a href="../verify/upload.php" class="btn btn-digital-id">Upload New Document</a>
        <a href="login.php" class="btn btn-logout">Logout</a>
    </div>

</body>
</html>
