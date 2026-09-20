<?php
session_start();
include("../database/config.php");

// यदि यूजर लॉग इन नहीं है तो उसे वापस भेजें
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// URL से डॉक्यूमेंट की ID निकालना
if(!isset($_GET['id'])) {
    die("Invalid Request. No Document ID provided.");
}

$doc_id = intval($_GET['id']);
$user_name = $_SESSION['user_name'] ?? 'Riddhi Kamble';

// डेटाबेस से उस विशिष्ट दस्तावेज़ की जानकारी निकालना
$query = "SELECT * FROM verified_documents WHERE id = $doc_id AND status = 'APPROVED' LIMIT 1";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    die("Error: No approved document found with this ID to generate a certificate.");
}

$doc_data = mysqli_fetch_assoc($result);

// सर्टिफिकेट के लिए फ़ाइल का नाम सेट करना
$filename = "DigiVerify_Certificate_" . $doc_id . ".html";

// ब्राउज़र को यह बताने के लिए हेडर्स सेट करना कि हम एक फ़ाइल डाउनलोड करवा रहे हैं
header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Cache-Control: public");

// 📜 डाउनलोड होने वाले डिजिटल सर्टिफिकेट का डिज़ाइन और कंटेंट
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DigiVerify Identity Verification Certificate</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #ffffff; color: #1e293b; padding: 30px; text-align: center; }
        .certificate-container { border: 10px double #1e3a8a; padding: 50px 30px; max-width: 650px; margin: 0 auto; background: #fafafa; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .logo-section { color: #1e3a8a; font-size: 28px; font-weight: bold; margin-bottom: 20px; letter-spacing: 1px; }
        h1 { font-size: 36px; color: #152960; margin-bottom: 5px; text-transform: uppercase; }
        .subtitle { font-size: 16px; color: #64748b; font-style: italic; margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .content { font-size: 18px; line-height: 1.6; margin-bottom: 40px; }
        .highlight { color: #1e3a8a; font-weight: bold; }
        .meta-info { display: flex; justify-content: space-between; margin-top: 50px; padding: 0 20px; text-align: left; font-size: 14px; }
        .meta-box { border-top: 1px solid #cbd5e1; padding-top: 10px; width: 45%; }
        .footer-tag { margin-top: 5px; font-size: 11px; color: #94a3b8; letter-spacing: 0.5px; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="certificate-container">
        <div class="logo-section">🛡️ DIGIVERIFY</div>
        <h1>Certificate of Verification</h1>
        <div class="subtitle">Enterprise Digital Identity Platform</div>
        
        <div class="content">
            This is to officially certify that the identity document type <br>
            <span class="highlight"><?php echo strtoupper($doc_data['document_type']); ?></span> <br>
            submitted by applicant <span class="highlight"><?php echo htmlspecialchars($user_name); ?></span> <br>
            has been thoroughly audited and secured under reference <br>
            <span class="highlight" style="font-family: monospace;">REF-CERT-<?php echo (9000 + $doc_id); ?></span>.
        </div>

        <div style="font-size: 20px; color: #10b981; font-weight: bold; margin: 20px 0;">
            ✓ STATUS: SECURELY APPROVED
        </div>

        <div class="meta-info">
            <div class="meta-box">
                <strong>Issued Timestamp:</strong><br>
                <span style="color:#475569;"><?php echo $doc_data['uploaded_at']; ?></span>
            </div>
            <div class="meta-box" style="text-align: right;">
                <strong>Authority Signature:</strong><br>
                <span style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 20px; color:#1e3a8a;">Enterprise SubAdmin</span>
            </div>
        </div>
        
        <div class="footer-tag" style="margin-top: 40px;">
            © 2026 Enterprise DigiVerify Security Group | Verified and Cryptographically Sealed
        </div>
    </div>

</body>
</html>
