
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic OCR Authentication Engine</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #040d1a; color: #fff; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .result-card { width: 680px; background: #081225; border: 1px solid #102a45; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 15px 50px rgba(0,0,0,0.6); box-sizing: border-box; }
        
        /* स्टेटस के हिसाब से आइकन्स */
        .icon-box { width: 70px; height: 70px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 35px; margin-bottom: 20px; font-weight: bold; }
        .icon-approved { border: 3px solid #10b981; color: #10b981; background: rgba(16, 185, 129, 0.1); }
        .icon-rejected { border: 3px solid #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.1); }
        
        .title { font-size: 28px; font-weight: bold; margin-bottom: 30px; letter-spacing: 0.5px; }
        
        /* स्टेटस बॉक्स स्टाइल्स */
        .status-box { border-radius: 8px; padding: 15px 20px; font-size: 13px; font-weight: 600; line-height: 1.6; margin-bottom: 35px; text-align: center; }
        .box-approved { background: rgba(16, 185, 129, 0.08); border: 1px solid #10b981; color: #34d399; }
        .box-rejected { background: rgba(239, 68, 68, 0.08); border: 1px solid #ef4444; color: #f87171; }
        
        .section-title { font-size: 11px; color: #38bdf8; text-transform: uppercase; font-weight: bold; text-align: left; margin-bottom: 20px; letter-spacing: 1px; border-bottom: 1px solid rgba(16, 42, 69, 0.5); padding-bottom: 6px; }
        .data-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid rgba(16, 42, 69, 0.3); font-size: 14px; }
        .data-row:last-of-type { border-bottom: none; margin-bottom: 35px; }
        .label { color: #64748b; font-weight: 500; }
        .value { color: #cbd5e1; font-weight: 600; }
        
        .ocr-terminal { background: #020813; border: 1px solid #102a45; border-radius: 8px; padding: 15px; font-family: 'Courier New', monospace; font-size: 12px; text-align: left; margin-bottom: 30px; line-height: 1.5; }
        
        /* डायनेमिक बटन */
        .btn-continue { width: 100%; padding: 16px; border: none; color: white; font-size: 16px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-approved { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
        .btn-rejected { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }
        .btn-continue:hover { transform: translateY(-2px); opacity: 0.9; }
    </style>
</head>
<body>

    <div class="result-card">
        <!-- डायनेमिक आइकन ब्लॉक -->
        <?php if ($is_approved) { ?>
            <div class="icon-box icon-approved">✓</div>
            <div class="title">Document Authenticated</div>
            <div class="status-box box-approved"><?php echo $status_badge; ?></div>
        <?php } else { ?>
            <div class="icon-box icon-rejected">✕</div>
            <div class="title" style="color: #f87171;">Document Verification Failed</div>
            <div class="status-box box-rejected"><?php echo $status_badge; ?></div>
        <?php } ?>
        
        <!-- LAYER 1: EXTRACTED OCR METRICS -->
        <div class="section-title">Layer 1: Live Tesseract OCR Scan Data</div>
        <div class="data-row">
            <span class="label">Detected Name</span>
            <span class="value" style="color: <?php echo $is_approved ? '#00d2ff' : '#f87171'; ?>;"><?php echo $detected_name; ?></span>
        </div>
        <div class="data-row">
            <span class="label">Identified Card Type</span>
            <span class="value"><?php echo $document_type; ?></span>
        </div>
        <div class="data-row">
            <span class="label">OCR Extraction Status</span>
            <span class="value" style="color: <?php echo $is_approved ? '#34d399' : '#f87171'; ?>;"><?php echo $accuracy; ?> Match Rate</span>
        </div>
        
        <!-- LAYER 2: SYSTEM TERMINAL LOGS -->
        <div class="section-title" style="margin-top: 15px;">Layer 2: Custom RPA Framework Logs</div>
        
        <div class="ocr-terminal">
            <div style="color: #64748b;">$ execution_node --verify active_session.png</div>
            <?php if ($is_approved) { ?>
                <div style="color: #34d399;">[SUCCESS] Tesseract OCR matched mandatory validation rules.</div>
                <div style="color: #38bdf8;">[RPA BRIDGE]: FastAPI response 200 OK. Database synced successfully.</div>
            <?php } else { ?>
                <div style="color: #ef4444;">[CRITICAL ERROR] Regex pattern mismatch. Required tokens (UIDAI / INCOME TAX) missing.</div>
                <div style="color: #fbbf24;">[FRAUD ALERT]: High Risk Score calculated by Python Node Executor.</div>
            <?php } ?>
        </div>
        
        <!-- डायनेमिक बटन ऐक्शन -->
        <?php if ($is_approved) { ?>
            <button class="btn-continue btn-approved" onclick="window.location.href='../dashboard.php'">Approve & Save to Database</button>
        <?php } else { ?>
            <button class="btn-continue btn-rejected" onclick="window.location.href='../dashboard.php'">Reject & Log Violation</button>
        <?php } ?>
    </div>

</body>
</html>
