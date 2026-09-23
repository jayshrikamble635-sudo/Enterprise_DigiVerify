<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocr_text'])) {

    $extracted_text = trim($_POST['ocr_text']);
    $js_name = isset($_POST['js_name']) ? trim($_POST['js_name']) : '';

    $score = 0;
    $reasons = [];

    /*
     * Normalize OCR text
     */
    $text = preg_replace('/\s+/', ' ', $extracted_text);
    $upperText = strtoupper($text);

    /*
     * ---------------------------------------
     * 1. Aadhaar number detection
     * ---------------------------------------
     */
    $aadhaar_no = "";

    if (preg_match('/\b([0-9]{4})[\s\-]*([0-9]{4})[\s\-]*([0-9]{4})\b/', $text, $matches)) {
        $aadhaar_no =
            $matches[1] . " " .
            $matches[2] . " " .
            $matches[3];

        $score += 30;
        $reasons[] = "Valid 12-digit Aadhaar number pattern detected.";
    } else {
        $reasons[] = "Aadhaar number pattern not detected.";
    }

    /*
     * ---------------------------------------
     * 2. Aadhaar keywords
     * ---------------------------------------
     */
    $aadhaarKeyword = false;

    if (
        stripos($upperText, 'AADHAAR') !== false ||
        strpos($text, 'आधार') !== false
    ) {
        $aadhaarKeyword = true;
        $score += 20;
        $reasons[] = "Aadhaar keyword detected.";
    } else {
        $reasons[] = "Aadhaar keyword not detected.";
    }

    /*
     * ---------------------------------------
     * 3. Government of India
     * ---------------------------------------
     */
    $governmentKeyword = false;

    if (
        stripos($upperText, 'GOVERNMENT OF INDIA') !== false ||
        stripos($upperText, 'GOVERNMENT') !== false ||
        stripos($upperText, 'INDIA') !== false ||
        strpos($text, 'भारत सरकार') !== false
    ) {
        $governmentKeyword = true;
        $score += 20;
        $reasons[] = "Government of India indicator detected.";
    } else {
        $reasons[] = "Government of India indicator not detected.";
    }

    /*
     * ---------------------------------------
     * 4. Name detection
     * ---------------------------------------
     */
    $detected_name = "";

    $lines = preg_split('/\r\n|\r|\n/', $extracted_text);

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line === '') {
            continue;
        }

        $cleanLine = strtoupper($line);

        if (
            strpos($cleanLine, 'GOVERNMENT') !== false ||
            strpos($cleanLine, 'INDIA') !== false ||
            strpos($line, 'भारत') !== false ||
            strpos($line, 'सरकार') !== false
        ) {
            continue;
        }

        if (
            strpos($cleanLine, 'AADHAAR') !== false ||
            strpos($line, 'आधार') !== false
        ) {
            continue;
        }

        if (preg_match('/[0-9]/', $line)) {
            continue;
        }

        if (strlen($line) >= 3 && strlen($line) <= 60) {
            $detected_name = $line;
            break;
        }
    }

    if ($detected_name === '' && $js_name !== '') {
        $detected_name = $js_name;
    }

    if ($detected_name !== '') {
        $score += 10;
        $reasons[] = "Possible holder name detected.";
    } else {
        $reasons[] = "Holder name could not be confidently detected.";
    }

    /*
     * ---------------------------------------
     * 5. Suspicious document indicators
     * ---------------------------------------
     */
    $suspiciousWords = [
        'DUPLICATE',
        'SAMPLE',
        'FAKE',
        'DEMO',
        'COPY',
        'SPECIMEN',
        'NOT VALID',
        'INVALID'
    ];

    $suspiciousFound = [];

    foreach ($suspiciousWords as $word) {
        if (stripos($upperText, $word) !== false) {
            $suspiciousFound[] = $word;
        }
    }

    if (count($suspiciousFound) > 0) {
        $score -= 50;

        $reasons[] =
            "Suspicious indicator detected: " .
            implode(', ', $suspiciousFound);
    }

    /*
     * ---------------------------------------
     * 6. OCR quality
     * ---------------------------------------
     */
    $ocrLength = strlen(trim($extracted_text));

    if ($ocrLength >= 80) {
        $score += 10;
        $reasons[] = "OCR text quality is sufficient.";
    } elseif ($ocrLength >= 40) {
        $score += 5;
        $reasons[] = "OCR text is partially readable.";
    } else {
        $reasons[] = "OCR text is too short.";
    }

    /*
     * ---------------------------------------
     * 7. Final score limit
     * ---------------------------------------
     */
    if ($score < 0) {
        $score = 0;
    }

    if ($score > 100) {
        $score = 100;
    }

    /*
     * ---------------------------------------
     * 8. Final decision
     *
     * APPROVED:
     * strong document-screening evidence
     *
     * REJECTED:
     * suspicious indicators or very poor evidence
     *
     * MANUAL REVIEW:
     * insufficient evidence
     * ---------------------------------------
     */

    if (count($suspiciousFound) > 0) {

        $status = "REJECTED";
        $status_type = "danger";

    } elseif (
        $aadhaarKeyword &&
        $governmentKeyword &&
        $aadhaar_no !== '' &&
        $score >= 70
    ) {

        $status = "APPROVED";
        $status_type = "success";

    } elseif ($score >= 45) {

        $status = "MANUAL REVIEW";
        $status_type = "warning";

    } else {

        $status = "REJECTED";
        $status_type = "danger";
    }

    /*
     * ---------------------------------------
     * Mask Aadhaar number
     * ---------------------------------------
     */
    $masked_aadhaar = "XXXX XXXX XXXX";

    if ($aadhaar_no !== '') {
        $parts = explode(' ', $aadhaar_no);

        if (count($parts) === 3) {
            $masked_aadhaar =
                "XXXX XXXX " . $parts[2];
        }
    }

    /*
     * ---------------------------------------
     * Encode result
     * ---------------------------------------
     */
    $params = [
        'status' => $status,
        'score' => $score,
        'name' => $detected_name,
        'aadhaar' => $masked_aadhaar,
        'reason' => implode('|', $reasons)
    ];

    header(
        "Location: verification_result.php?" .
        http_build_query($params)
    );

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Enterprise DigiVerify - Aadhaar Verification</title>

<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    background:
        radial-gradient(circle at top left, #12345b, transparent 40%),
        radial-gradient(circle at bottom right, #063b45, transparent 40%),
        #050b16;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 25px;
}

.container {
    width: 100%;
    max-width: 700px;
    background: rgba(8, 20, 38, 0.95);
    border: 1px solid #1d6f91;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 0 40px rgba(0, 190, 255, 0.15);
}

.logo {
    text-align: center;
    font-size: 30px;
    font-weight: bold;
    color: #42d9ff;
}

.subtitle {
    text-align: center;
    color: #9fb5c9;
    margin: 10px 0 30px;
}

.upload-box {
    border: 2px dashed #2386a8;
    border-radius: 15px;
    padding: 35px;
    text-align: center;
}

input[type="file"] {
    width: 100%;
    padding: 15px;
    background: #0c1a2c;
    color: white;
    border-radius: 10px;
    border: 1px solid #31556e;
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 15px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(90deg, #00a8e8, #00d4aa);
    color: #001018;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    opacity: .9;
}

#loading-box {
    display: none;
    margin-top: 25px;
    padding: 20px;
    border-radius: 12px;
    background: #0b1b2e;
    border: 1px solid #24728e;
}

#status-text {
    color: #42d9ff;
    text-align: center;
}

.notice {
    margin-top: 25px;
    font-size: 13px;
    line-height: 1.6;
    color: #a9bac8;
    text-align: center;
}

</style>

</head>

<body>

<div class="container">

    <div class="logo">
        Enterprise DigiVerify
    </div>

    <div class="subtitle">
        AI-Assisted Aadhaar Document Screening
    </div>

    <div class="upload-box">

        <input
            type="file"
            id="file-input"
            accept="image/jpeg,image/png,image/webp"
        >

        <button onclick="startVerification()">
            🔍 Verify Document
        </button>

    </div>

    <div id="loading-box">
        <div id="status-text">
            Initializing OCR...
        </div>
    </div>

    <div class="notice">
        This system performs project-level OCR and document screening.
        It does not perform official UIDAI authentication.
    </div>

    <form method="POST" id="main-form">

        <input
            type="hidden"
            name="ocr_text"
            id="ocr-hidden-input"
        >

        <input
            type="hidden"
            name="js_name"
            id="js-name-input"
        >

    </form>

</div>

<script>

async function startVerification() {

    const input = document.getElementById("file-input");

    if (!input.files || input.files.length === 0) {
        alert("Please select an Aadhaar document image.");
        return;
    }

    const file = input.files[0];

    document.getElementById("loading-box").style.display = "block";

    const statusText = document.getElementById("status-text");

    try {

        statusText.textContent = "Starting AI OCR...";

        const result = await Tesseract.recognize(
            file,
            "eng",
            {
                logger: function(message) {

                    if (message.status === "recognizing text") {

                        const percentage =
                            Math.floor(message.progress * 100);

                        statusText.textContent =
                            "Analyzing document: " +
                            percentage +
                            "%";
                    }

                }
            }
        );

        const text = result.data.text || "";

        document.getElementById("ocr-hidden-input").value = text;

        /*
         * Try to identify a possible name.
         */
        let extractedName = "";

        const lines = text.split(/\r?\n/);

        for (let i = 0; i < lines.length; i++) {

            const current = lines[i].trim().toUpperCase();

            if (
                current.includes("GOVERNMENT") ||
                current.includes("INDIA")
            ) {

                if (lines[i + 1]) {

                    const candidate =
                        lines[i + 1].trim();

                    if (
                        candidate.length >= 3 &&
                        !/[0-9]/.test(candidate)
                    ) {
                        extractedName = candidate;
                        break;
                    }
                }
            }
        }

        document.getElementById("js-name-input").value =
            extractedName;

        statusText.textContent =
            "OCR completed. Running verification rules...";

        setTimeout(function() {

            document.getElementById("main-form").submit();

        }, 500);

    } catch (error) {

        console.error(error);

        statusText.textContent =
            "OCR failed.";

        alert(
            "Document analysis failed. Please use a clear JPG or PNG image."
        );
    }
}

</script>

</body>
</html>
