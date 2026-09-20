<?php
session_start();
include("../database/config.php");

// यदि यूजर लॉग इन नहीं है तो उसे वापस भेजें
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Riddhi Kamble';
$user_email = $_SESSION['user_email'] ?? 'riddhi@gmail.com';

// 🔴 सुधार: बड़े अक्षरों में 'APPROVED' स्थिति की जांच करना और संरचना से मेल खाना
$query = "SELECT * FROM verified_documents WHERE status = 'APPROVED' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

// यदि कोई APPROVED दस्तावेज़ नहीं मिलता है, तो हम डिफ़ॉल्ट रूप से पहले उपलब्ध दस्तावेज़ को लोड करेंगे
if(mysqli_num_rows($result) == 0) {
    $fallback_query = "SELECT * FROM verified_documents ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $fallback_query);
}

$doc_found = false;
if(mysqli_num_rows($result) > 0) {
    $doc_data = mysqli_fetch_assoc($result);
    $doc_found = true;
    
    // कार्ड डिस्प्ले वैल्यूज सेट करना
    $doc_id = "DIGI-" . (8800 + $doc_data['id']);
    $doc_type = strtoupper($doc_data['document_type']);
    $issue_date = isset($doc_data['uploaded_at']) ? date("Y-m-d", strtotime($doc_data['uploaded_at'])) : "2026-08-12";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital ID Card | DigiVerify</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; }
        
        /* टेक थीम डिजिटल आईडी कार्ड कंटेनर */
        .id-card { background: linear-gradient(135deg, #0b1528 0%, #112544 100%); width: 380px; border-radius: 16px; border: 1px solid #1d4ed8; box-shadow: 0 15px 35px rgba(0, 130, 255, 0.2); overflow: hidden; position: relative; padding: 25px; box-sizing: border-box; }
        .id-card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(14,165,233,0.05) 0%, transparent 60%); pointer-events: none; }
        
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed #1e293b; padding-bottom: 15px; margin-bottom: 20px; }
        .card-header h3 { margin: 0; font-size: 18px; color: #00d2ff; display: flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
        .enterprise-tag { font-size: 10px; background: rgba(37, 99, 235, 0.2); color: #38bdf8; border: 1px solid #2563eb; padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        
        .card-body { display: flex; gap: 20px; align-items: center; }
        .photo-slot { width: 90px; height: 110px; background: #070f1e; border: 1px solid #1e3a8a; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #38bdf8; position: relative; }
        .photo-slot i { font-size: 40px; }
        .verif-chip { position: absolute; bottom: 5px; background: #10b981; color: white; font-size: 8px; font-weight: bold; padding: 1px 5px; border-radius: 3px; text-transform: uppercase; }
        
        .info-fields { flex: 1; display: flex; flex-direction: column; gap: 10px; text-align: left; }
        .field { display: flex; flex-direction: column; }
        .label { font-size: 10px; color: #52789c; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
        .value { font-size: 14px; color: #f1f5f9; font-weight: 600; margin-top: 1px; }
        
        .card-footer { margin-top: 25px; border-top: 1px solid #1e293b; padding-top: 15px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #64748b; }
        .barcode-simulation { letter-spacing: 2px; font-family: monospace; color: #38bdf8; font-size: 12px; }
        
        .btn-back { margin-top: 30px; padding: 12px 25px; background: rgba(255, 255, 255, 0.05); border: 1px solid #1e293b; color: #94a3b8; border-radius: 8px; font-size: 14px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-back:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
    </style>
</head>
<body>

    <?php if($doc_found): ?>
        <!-- DIGITAL COMPACT ID CARD VIEW -->
        <div class="id-card">
            <div class="card-header">
                <h3><i class="fas fa-shield-halved"></i> DIGIVERIFY</h3>
                <span class="enterprise-tag">DIGITAL ID</span>
            </div>
            
            <div class="card-body">
                <div class="photo-slot">
                    <i class="fas fa-user-astronaut"></i>
                    <span class="verif-chip"><?php echo htmlspecialchars($doc_data['status']); ?></span>
                </div>
                
                <div class="info-fields">
                    <div class="field">
                        <span class="label">Full Name</span>
                        <span class="value"><?php echo htmlspecialchars($user_name); ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Digital ID Number</span>
                        <span class="value" style="color: #00d2ff; font-family: monospace;"><?php echo $doc_id; ?></span>
                    </div>
                    <div class="field">
                        <span class="label">Verified Credential</span>
                        <span class="value" style="font-size: 12px;"><?php echo $doc_type; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div>
                    <span class="label" style="display:block;">Issued On</span>
                    <span style="color:#cbd5e1; font-weight:600;"><?php echo $issue_date; ?></span>
                </div>
                <div class="barcode-simulation">
                    ||||| | |||| ||| |
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- FALLBACK NO LOG FOUND WARNING BOX -->
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; padding: 25px; border-radius: 8px; max-width: 400px; text-align: center;">
            <i class="fas fa-exclamation-triangle fa-2x" style="color: #ef4444; margin-bottom: 10px;"></i>
            <h3 style="margin: 0 0 5px 0; color: #fff;">No Document Log Discovered</h3>
            <p style="margin: 0; font-size: 14px; color: #94a3b8;">Please wait until the Sub Administrator completes your pending identity verification log audit.</p>
        </div>
    <?php endif; ?>

    <!-- BACK TO DASHBOARD ACTION LINK -->
    <a href="dashboard.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Return to Dashboard
    </a>

</body>
</html>
