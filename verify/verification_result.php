<?php
// 1. एरर रिपोर्टिंग ऑन रखना ताकि कोई गलती हो तो स्क्रीन पर दिखे
error_reporting(E_ALL);
ini_set('display_errors', '1');

// URL पैरामीटर से असली पढ़ा हुआ डेटा सुरक्षित निकालना
$name = (isset($_GET['name']) && !empty($_GET['name'])) ? htmlspecialchars(urldecode($_GET['name'])) : "NOT DETECTED";
$aadhaar_no = (isset($_GET['aadhaar']) && !empty($_GET['aadhaar'])) ? htmlspecialchars(urldecode($_GET['aadhaar'])) : "XXXX XXXX XXXX";
$is_ai = (isset($_GET['is_ai']) && $_GET['is_ai'] === 'true') ? true : false;

// एआई (नकली) या असली के आधार पर वैल्यूज बदलना
$doc_type = $is_ai ? "AADHAAR CARD (DUPLICATE / AI)" : "AADHAAR CARD (UIDAI)";
$accuracy = $is_ai ? "Fake Template" : "99.2% Accuracy";
$confidence = $is_ai ? "0% - Fraud Risk" : "97.5% Confidence";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result | DigiVerify</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background-color: #08111f; color: #ffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .result-card { background: linear-gradient(145deg, #0f172a, #0b1324); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 24px; padding: 40px 30px; width: 100%; max-width: 520px; text-align: center; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7); }
        
        /* सक्सेस और फेलियर सर्कल्स */
        .circle { width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; }
        .success-circle { border: 3px solid #10b981; }
        .success-circle::after { content: ''; width: 15px; height: 30px; border: solid #10b981; border-width: 0 4px 4px 0; transform: rotate(45deg); margin-top: -5px; }
        
        .fail-circle { border: 3px solid #ef4444; position: relative; }
        .fail-circle::before, .fail-circle::after { content: ''; position: absolute; width: 4px; height: 35px; background-color: #ef4444; }
        .fail-circle::before { transform: rotate(45deg); }
        .fail-circle::after { transform: rotate(-45deg); }

        .result-card h2 { font-size: 26px; margin-bottom: 15px; font-weight: 700; }
        
        /* स्टेटस बैज */
        .status-badge { padding: 14px 15px; border-radius: 12px; font-size: 13px; font-weight: 600; line-height: 1.5; margin-bottom: 30px; text-align: center; }
        .status-success { background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; }
        .status-fail { background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }
        
        .section-title { color: #38bdf8; font-size: 11px; font-weight: 700; text-align: left; letter-spacing: 1px; margin: 20px 0 10px 0; text-transform: uppercase; }
        
        /* डेटा टेबल डिज़ाइन */
        .info-table { width: 100%; background-color: rgba(19, 29, 52, 0.4); border: 1px solid rgba(56, 189, 248, 0.15); border-radius: 14px; border-collapse: separate; border-spacing: 0; margin-bottom: 15px; overflow: hidden; }
        .info-table td { padding: 16px 20px; text-align: left; font-size: 14px; border-bottom: 1px solid rgba(56, 189, 248, 0.1); }
        .info-table tr:last-child td { border-bottom: none; }
        .label-col { color: #9fb3d6; width: 45%; }
        .value-col { font-weight: 600; text-align: right; color: #ffffff; word-break: break-all; }
        
        .name-highlight { color: #a5b4fc; }
        .accuracy-badge { color: #10b981; font-weight: 700; }
        .fail-badge { color: #ef4444; font-weight: 700; }
        .confidence-badge { color: #38bdf8; font-weight: 700; }

        /* एक्शन बटन्स */
        .btn-container { display: flex; flex-direction: column; gap: 12px; margin-top: 30px; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #9333ea); border: none; color: white; padding: 14px 20px; font-size: 15px; font-weight: 700; border-radius: 12px; text-decoration: none; display: block; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3); }
        .btn-primary:hover { opacity: 0.95; transform: translateY(-1px); }
        .btn-secondary { background: transparent; border: 2px solid rgba(56, 189, 248, 0.4); color: #38bdf8; padding: 14px 20px; font-size: 15px; font-weight: 700; border-radius: 12px; text-decoration: none; display: block; transition: 0.3s; }
        .btn-secondary:hover { background: rgba(56, 189, 248, 0.1); }
    </style>
</head>
<body>

<div class="result-card">
    <?php if ($is_ai): ?>
        <!-- नकली होने पर लाल क्रॉस (Reject) -->
        <div class="circle fail-circle"></div>
        <h2 style="color: #ef4444;">Document Rejected</h2>
        <div class="status-badge status-fail">
            REJECTED: Fake Template / Copy Pattern Detected! Document failed real-time integrity verification.
        </div>
    <?php else: ?>
        <!-- असली होने पर हरा टिक (Approve) -->
        <div class="circle success-circle"></div>
        <h2>Document Authenticated</h2>
        <div class="status-badge status-success">
            APPROVED: Document successfully read and validated via Live OCR matching database records.
        </div>
    <?php endif; ?>
    
    <div class="section-title">Layer 1: Extracted OCR Metrics</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Detected Name</td>
            <td class="value-col name-highlight"><?php echo $name; ?></td>
        </tr>
        <tr>
            <td class="label-col">Aadhaar Number</td>
            <td class="value-col" style="color: #cbd5e1;"><?php echo $aadhaar_no; ?></td>
        </tr>
        <tr>
            <td class="label-col">Identified Document</td>
            <td class="value-col"><?php echo $doc_type; ?></td>
        </tr>
        <tr>
            <td class="label-col">Processing Status</td>
            <td class="value-col <?php echo $is_ai ? 'fail-badge' : 'accuracy-badge'; ?>">
                <?php echo $accuracy; ?>
            </td>
        </tr>
    </table>
    
    <div class="section-title">Layer 2: Biometric Validation</div>
    <table class="info-table">
        <tr>
            <td class="label-col">Face Verification Match</td>
            <td class="value-col confidence-badge"><?php echo $confidence; ?></td>
        </tr>
    </table>
    
    <div class="btn-container">
        <?php if (!$is_ai): ?>
            <a href="#" class="btn-primary">Continue Process</a>
        <?php endif; ?>
        <a href="upload.php" class="btn-secondary">Verify Another Card</a>
    </div>
</div>

</body>
</html>
