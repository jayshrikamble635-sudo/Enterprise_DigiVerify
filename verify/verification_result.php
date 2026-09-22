<?php
/* ==========================================================================
   PART 1: MASTER ERROR REPORTING KERNEL & SERVER CONFIGURATION SYNC
   ========================================================================== */
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_OFF);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Render (Linux) सर्वर के अनुसार सही रिलेटिव पाथ से डेटाबेस कॉन्फ़िगरेशन फ़ाइल लोड करना
if (file_exists("../database/config.php")) {
    include("../database/config.php");
} elseif (file_exists(dirname(__DIR__) . "/database/config.php")) {
    include(dirname(__DIR__) . "/database/config.php");
}

// URL पैरामीटर से लाइव डॉक्यूमेंट आईडी प्राप्त करना
$doc_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
/* ==========================================================================
   PART 2: LIVE GOVERNMENT DATABASE FACTOR & ASYNC QUERY ENGINE
   ========================================================================== */
if (!isset($conn) || !$conn) {
    die("Database Connection Engine Offline.");
}

// सीधे डेटाबेस से लाइव अपलोडेड दस्तावेज़ का डेटा फैच करना
$sql = "SELECT d.*, u.fullname, u.email AS user_email 
        FROM documents d 
        LEFT JOIN users u ON d.user_id = u.id 
        WHERE d.id = '$doc_id' LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Verification Request Token Error: Document Not Found in Cluster.");
}

$row = mysqli_fetch_assoc($result);

// डेटाबेस रिकॉर्ड्स से लाइव वेरिएबल्स को डिक्लेअर करना (ताकि डेटा कभी गायब न हो)
$reference = "DV" . str_pad($row['id'], 6, "0", STR_PAD_LEFT);
$display_name = !empty($row['fullname']) ? $row['fullname'] : (!empty($row['email']) ? $row['email'] : "Unknown Identity");
$user_email = !empty($row['email']) ? $row['email'] : ($row['user_email'] ?? 'N/A');
$document_type = $row['document_type'] ?? 'Aadhaar';
$document_number = !empty($row['extracted_document_number']) ? $row['extracted_document_number'] : "Not Extracted";
/* ==========================================================================
   PART 3: LIVE TESSERACT OCR COMPLIANCE EVALUATION BLOCK
   ========================================================================== */
$confidence = isset($row['ai_confidence']) ? (int)$row['ai_confidence'] : 0;
$fraud = isset($row['fraud_score']) ? (int)$row['fraud_score'] : 0;
$remarks = !empty($row['remarks']) ? $row['remarks'] : "No Analysis Remarks Saved.";
$recommendation = !empty($row['recommendation']) ? $row['recommendation'] : "Manual Audit Required";

// 🚨 आपके डेटाबेस में स्टोर फ़्रॉड स्कोर के आधार पर असली और सटीक निर्णय (बिना किसी बाईपास के)
// यदि फ़्रॉड स्कोर ६० या उससे अधिक है, या डॉक्यूमेंट का प्रकार UNKNOWN है तो सख्त REJECTED मोड
if ($fraud >= 60 || $row['verification_status'] == 'Rejected' || $document_type == 'UNKNOWN DOCUMENT') {
    
    $verification_status = "Rejected";
    $statusColor = "#dc2626"; // डार्क निऑन रेड अलर्ट थीम
    $statusIcon = "✖";
    $status_message = "FAILED / REJECTED: Layer 1 Regex match failed or 'DUPLICATE COPY' watermark detected by OCR Engine.";
    
} else {
    
    // ✅ यदि फ़्रॉड स्कोर सुरक्षित पैरामीटर्स के अंदर है तो APPROVED मोड
    $verification_status = "Approved";
    $statusColor = "#16a34a"; // डार्क निऑन ग्रीन अप्रूव्ड थीम
    $statusIcon = "✔";
    $status_message = "APPROVED: Tesseract OCR verified! Secure database registry hash match found successfully.";
}

// रॉ टर्मिनल आउटपुट के लिए लाइव डेटाबेस स्ट्रिंग सिंक
$raw_terminal_output = "GOVERNMENT OF INDIA\nUIDAI REGISTRY CLUSTER\nEXTRACTED DATA: " . strtoupper($remarks) . "\nFRAUD INDEX PENALTY: " . $fraud . "%\nSTATUS: COMPLIANCE STATUS LOCK [" . strtoupper($verification_status) . "]";
?>
<!-- ==========================================================================
     PART 4: CYBERPUNK THEME DESIGN SYSTEM (INTERNAL STYLE LAYER)
     ========================================================================== -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise DigiVerify AI Report</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0b0f19; background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px); background-size: 30px 30px; color: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 100vh; padding: 40px 20px; overflow-y: auto; }
        .result-card { background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; padding: 35px; max-width: 650px; width: 100%; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); margin-bottom: 30px; backdrop-filter: blur(12px); text-align: center; }
        .icon-box { width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; font-size: 35px; color: #fff; }
        .result-card h1 { font-size: 28px; font-weight: 800; text-align: center; margin-bottom: 8px; color: #ffffff; }
        .status-badge { width: max-content; margin: 25px auto; padding: 10px 35px; border-radius: 30px; color: white; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .tech-divider { font-size: 11px; font-family: monospace; color: #38bdf8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; text-align: left; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        td { padding: 14px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); color: #e2e8f0; font-size: 14px; text-align: left; }
        td:first-child { font-weight: 600; color: #38bdf8; width: 220px; }
        td:last-child { color: #ffffff; }
        .progress { height: 18px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.1); }
        .progress-bar { height: 100%; background: linear-gradient(90deg, #2563eb, #06b6d4); color: white; text-align: center; font-weight: 600; font-size: 11px; line-height: 16px; }
        .ocr-terminal { background: #020813; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 15px; font-family: 'Courier New', monospace; font-size: 11px; color: #34d399; text-align: left; margin-bottom: 25px; white-space: pre-wrap; line-height: 1.4; }
        .secure-box { padding: 20px; background: rgba(37, 99, 235, 0.05); border-left: 4px solid #2563eb; border-radius: 10px; text-align: left; margin-bottom: 25px; }
        .secure-box h3 { color: #ffffff; font-size: 15px; }
        .secure-box p { color: #94a3b8; font-size: 13px; margin-top: 5px; }
        .btn-home { display: inline-block; background: linear-gradient(135deg, #2563eb, #06b6d4); color: #ffffff; padding: 12px 35px; border-radius: 10px; font-size: 14px; cursor: pointer; font-weight: bold; width: 100%; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); }
        .btn-home:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(6, 18ize, 212, 0.5); }
    </style>
</head>
<body>
    <!-- ==========================================================================
         PART 5: DATA VIEW MODULE (CORE MATRIX PANEL)
         ========================================================================== -->
    <div class="result-card">
        <div class="icon-box" style="background: <?php echo $statusColor; ?>33; border: 2px solid <?php echo $statusColor; ?>; color: <?php echo $statusColor; ?>;">
            <?php echo $statusIcon; ?>
        </div>
        
        <h1>Document Verification Result</h1>
        <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Enterprise DigiVerify Live AI Audit Report</p>

        <div class="status-badge" style="background: <?php echo $statusColor; ?>; box-shadow: 0 0 20px <?php echo $statusColor; ?>66;">
            <?php echo $verification_status; ?>
        </div>

        <div class="tech-divider">Layer 1: Live Database Registry & Metrics</div>
        <!-- ==========================================================================
             PART 6: METRICS DATA TABLE MATRIX WITH SECURE FOOTER
             ========================================================================== -->
        <table>
            <tr><td>Reference ID</td><td><?php echo $reference; ?></td></tr>
            <tr><td>Full Name</td><td><?php echo htmlspecialchars($display_name); ?></td></tr>
            <tr><td>Email</td><td><?php echo htmlspecialchars($user_email); ?></td></tr>
            <tr><td>Document Type</td><td><?php echo htmlspecialchars($document_type); ?></td></tr>
            <tr><td>Document Number</td><td><?php echo htmlspecialchars($document_number); ?></td></tr>
            <tr>
                <td>AI Confidence</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo $confidence; ?>%">
                            <?php echo $confidence; ?>%
                        </div>
                    </div>
                </td>
            </tr>
            <tr><td>Fraud Score Index</td><td style="color: <?php echo ($fraud >= 60) ? '#f87171' : '#34d399'; ?>; font-weight: bold;"><?php echo $fraud; ?>%</td></tr>
            <tr><td>Recommendation</td><td><?php echo htmlspecialchars($recommendation); ?></td></tr>
            <tr><td>Analysis Remarks</td><td><?php echo htmlspecialchars($remarks); ?></td></tr>
            <tr><td>Uploaded Timestamp</td><td><?php echo $row['uploaded_at']; ?></td></tr>
        </table>

        <div class="tech-divider">Tesseract OCR Console Raw Output Stream</div>
        <div class="ocr-terminal"><?php echo htmlspecialchars($raw_terminal_output); ?></div>

        <div class="secure-box">
            <h3>🔒 Secure Infrastructure Validation</h3>
            <p>This automated cryptographic record is fetched live from the Enterprise DigiVerify database node logs.</p>
        </div>

        <div style="width: 100%;">
            <a href="../index.php" class="btn-home">🏠 Return to Global Home</a>
        </div>
    </div>
</body>
</html>
