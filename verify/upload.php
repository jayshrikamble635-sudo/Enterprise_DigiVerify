<?php
// डीबगिंग चालू रखें
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";
$message_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document_file'])) {
    
    // 🔥 सीधे बिना किसी वेरिएबल या सिंबल की गलती के कनेक्शन फ़ंक्शन का उपयोग
  $conn = @new mysqli(
    "आपका-डेटाबेस-होस्ट-यहाँ-डालें.clever-cloud.com", // 💡 यहाँ अपना पूरा Clever Cloud Host एड्रेस लिखें
    "usmmcxltshqjsde2", 
    "4yIROXJGxupdTdzB6dZm", 
    "bnljgn27equjadrmm6w7", 
    3306
);

    
    if ($conn && !$conn->connect_error) {
        $conn->set_charset("utf8mb4");

        // uploads फ़ोल्डर का रास्ता
        $target_dir = dirname(__DIR__) . "/uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = "sample_id.jpg";
        $target_file = $target_dir . $file_name;
        
        $file_mime = $_FILES["document_file"]["type"];
        $allowed_mimes = ['image/jpeg', 'image/jpg', 'image/png'];

        $is_graphic_image = false;
        if (in_array($file_mime, $allowed_mimes)) {
            $img_test = @imagecreatefromstring(file_get_contents($_FILES["document_file"]["tmp_name"]));
            if ($img_test !== false) {
                $is_graphic_image = true;
                imagedestroy($img_test);
            }
        }

        if (!$is_graphic_image) {
            $message = "REJECTED: Invalid File Format! Only real JPG, JPEG, and PNG images are allowed.";
            $message_class = "error-msg";
        } else {
            if (file_exists($target_file)) {
                @unlink($target_file);
            }

            if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO verifications (id, name, document_type, ocr_score, face_match, document_image, status) 
                        VALUES (98, 'PENDING', 'UNKNOWN', '0.0', '0.0', 'sample_id.jpg', 'PENDING')
                        ON DUPLICATE KEY UPDATE status='PENDING', name='PENDING', document_type='UNKNOWN'";
                
                $conn->query($sql);
                $conn->close();

                // सीधे verification_result.php पर रीडायरेक्ट करें
                header("Location: verification_result.php?id=98");
                exit();
            } else {
                $message = "ERROR: Failed to save uploaded document.";
                $message_class = "error-msg";
            }
        }
    } else {
        $message = "ERROR: Database connection offline. " . ($conn ? $conn->connect_error : "");
        $message_class = "error-msg";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Document Secure Upload | DigiVerify</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #08111f; color: #fff; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 40px 0; }
        .upload-card { background: linear-gradient(145deg, #0f172a, #0b1324); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 24px; padding: 40px; max-width: 500px; width: 90%; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7); text-align: center; }
        h1 { font-size: 24px; font-weight: 800; margin-bottom: 10px; }
        p { color: #9fb3d6; font-size: 14px; margin-bottom: 30px; line-height: 1.5; }
        .file-box { border: 2px dashed rgba(56, 189, 248, 0.4); padding: 30px; border-radius: 14px; margin-bottom: 25px; background: rgba(19, 29, 52, 0.4); cursor: pointer; position: relative; }
        .file-box input[type="file"] { position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .btn-submit { background: linear-gradient(135deg, #2563eb, #9333ea); color: #fff; border: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; width: 100%; cursor: pointer; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3); transition: 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(147, 51, 234, 0.4); }
        .btn-back { background: transparent; color: #38bdf8; border: 2px solid rgba(56, 189, 248, 0.4); padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; width: 100%; cursor: pointer; transition: 0.3s; }
        .btn-back:hover { background: rgba(56, 189, 248, 0.1); border-color: #38bdf8; transform: translateY(-2px); }
        .error-msg { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 15px; border-radius: 8px; color: #fca5a5; font-size: 13px; margin-bottom: 20px; text-align: left; font-family: monospace; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="upload-card">
        <h1>AI Document Secure Upload</h1>
        <p>Please upload a clear scanned image of your Aadhaar, PAN, Voter ID, Driving Licence, or Passport for real-time verification.</p>
        <?php if (!empty($message)): ?>
            <div class="error-msg"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="file-box">
                <span style="color: #38bdf8; font-weight: 600;">Click to browse files</span>
                <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Supports: JPG, JPEG, PNG</div>
                <input type="file" name="document_file" required>
            </div>
            <div style="display: flex; gap: 15px; width: 100%;">
                <button type="button" onclick="history.back()" class="btn-back">Back</button>
                <button type="submit" class="btn-submit">Upload & Verify Live</button>
            </div>
        </form>
    </div>
</body>
</html>
