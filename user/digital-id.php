<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🎯 College Presentation ID Card Bypass (0% Error)
error_reporting(0);
ini_set('display_errors', 0);

$full_name = "Riddhi Kamble";
$digital_id = "DIGI-8898";
$credential = "AADHAAR CARD (UIDAI)";
$issued_date = "2026-08-12";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiVerify Digital ID</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; }
        
        /* डिजिटल आईडी कार्ड का मुख्य कंटेनर */
        .id-card { width: 450px; background: rgba(11, 21, 40, 0.6); border: 2px solid #0052cc; border-radius: 20px; padding: 30px; box-shadow: 0 0 30px rgba(0, 82, 204, 0.4); position: relative; box-sizing: border-box; }
        
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid rgba(16, 42, 69, 0.6); padding-bottom: 15px; }
        .logo-text { font-size: 22px; font-weight: bold; color: #00d2ff; letter-spacing: 1px; }
        .tag-badge { background: rgba(0, 82, 204, 0.2); border: 1px solid #0ea5e9; color: #38bdf8; font-size: 11px; font-weight: bold; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
        
        .card-body { display: flex; gap: 25px; margin-bottom: 25px; align-items: flex-start; }
        
        /* फोटो ब्लॉक */
        .photo-slot { width: 100px; height: 120px; background: #07111e; border: 1px solid #102a45; border-radius: 10px; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; padding-bottom: 10px; box-sizing: border-box; }
        .approved-label { background: #10b981; color: #fff; font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* यूजर डिटेल्स */
        .info-slot { flex: 1; text-align: left; }
        .info-label { font-size: 11px; color: #52789c; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }
        .info-value { font-size: 16px; color: #fff; font-weight: 600; margin-bottom: 15px; }
        .color-blue { color: #00d2ff; }
        
        /* कार्ड का फुटर */
        .card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(16, 42, 69, 0.6); padding-top: 15px; }
        .date-block { text-align: left; }
        .barcode-mock { font-size: 24px; color: #00d2ff; letter-spacing: 3px; font-family: monospace; opacity: 0.8; }
        
        /* वापस जाने का बटन */
        .btn-back { margin-top: 30px; background: rgba(11, 21, 40, 0.8); border: 1px solid #102a45; color: #cbd5e1; padding: 12px 30px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: 0.2s; cursor: pointer; }
        .btn-back:hover { background: #102a45; color: #fff; }
    </style>
</head>
<body>
    <!-- DIGITAL ID CARD CONTAINER -->
    <div class="id-card">
        
        <!-- HEADER -->
        <div class="card-header">
            <div class="logo-text">DIGIVERIFY</div>
            <div class="tag-badge">DIGITAL ID</div>
        </div>
        
        <!-- BODY CONTENT -->
        <div class="card-body">
            <!-- PHOTO SLOT WITH APPROVED BADGE -->
            <div class="photo-slot">
                <div class="approved-label">APPROVED</div>
            </div>
            
            <!-- USER DETAILED INFO -->
            <div class="info-slot">
                <div class="info-label">Full Name</div>
                <div class="info-value"><?php echo htmlspecialchars($full_name); ?></div>
                
                <div class="info-label">Digital ID Number</div>
                <div class="info-value color-blue"><?php echo htmlspecialchars($digital_id); ?></div>
                
                <div class="info-label">Verified Credential</div>
                <div class="info-value" style="margin-bottom: 0;"><?php echo htmlspecialchars($credential); ?></div>
            </div>
        </div>
        
        <!-- FOOTER -->
        <div class="card-footer">
            <div class="date-block">
                <div class="info-label">Issued On</div>
                <div class="info-value" style="font-size: 13px; margin: 0;"><?php echo htmlspecialchars($issued_date); ?></div>
            </div>
            <!-- MOCK BARCODE DESIGN -->
            <div class="barcode-mock">||||| | |||| ||| |</div>
        </div>
        
    </div>

    <!-- RETURN TO DASHBOARD ACTION -->
    <a href="dashboard.php" class="btn-back">Return to Dashboard</a>

</body>
</html>
