<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocr_text'])) {

    $ocr = trim($_POST['ocr_text']);
    $jsName = isset($_POST['js_name']) ? trim($_POST['js_name']) : '';

    $score = 0;
    $reasons = [];
    $warnings = [];

    /*
    =========================================================
    NORMALIZE OCR
    =========================================================
    */

    $text = preg_replace('/[ \t]+/', ' ', $ocr);
    $upper = strtoupper($text);

    /*
    =========================================================
    1. AADHAAR NUMBER
    =========================================================
    */

    $aadhaar = '';

    $numberPatterns = [
        '/\b([0-9]{4})[\s\-]+([0-9]{4})[\s\-]+([0-9]{4})\b/',
        '/\b([0-9]{4})([0-9]{4})([0-9]{4})\b/'
    ];

    foreach ($numberPatterns as $pattern) {

        if (preg_match($pattern, $text, $m)) {

            $aadhaar =
                $m[1] . ' ' .
                $m[2] . ' ' .
                $m[3];

            break;
        }
    }

    if ($aadhaar !== '') {

        $score += 30;

        $reasons[] =
            '12-digit Aadhaar number pattern detected.';

    } else {

        $warnings[] =
            'Aadhaar number was not clearly detected.';
    }


    /*
    =========================================================
    2. AADHAAR KEYWORD
    =========================================================
    */

    $aadhaarKeyword = false;

    $aadhaarWords = [
        'AADHAAR',
        'AADHAR',
        'आधार'
    ];

    foreach ($aadhaarWords as $word) {

        if (
            stripos($upper, strtoupper($word)) !== false ||
            strpos($text, $word) !== false
        ) {

            $aadhaarKeyword = true;
            break;
        }
    }

    if ($aadhaarKeyword) {

        $score += 20;

        $reasons[] =
            'Aadhaar identity indicator detected.';

    } else {

        $warnings[] =
            'Aadhaar keyword was not clearly detected.';
    }


    /*
    =========================================================
    3. GOVERNMENT / INDIA
    =========================================================
    */

    $govKeyword = false;

    $governmentPatterns = [
        'GOVERNMENT OF INDIA',
        'GOVT OF INDIA',
        'GOVERNMENT',
        'GOVT.',
        'INDIA',
        'भारत सरकार',
        'भारत'
    ];

    foreach ($governmentPatterns as $word) {

        if (
            stripos($upper, strtoupper($word)) !== false ||
            strpos($text, $word) !== false
        ) {

            $govKeyword = true;
            break;
        }
    }

    if ($govKeyword) {

        $score += 15;

        $reasons[] =
            'Government/India indicator detected.';

    } else {

        $warnings[] =
            'Government/India indicator not clearly detected.';
    }


    /*
    =========================================================
    4. DOB CHECK
    =========================================================
    */

    $dobFound = false;

    $dobPatterns = [
        '/\b[0-3]?[0-9][\/\-][0-1]?[0-9][\/\-][12][0-9]{3}\b/',
        '/\b[12][0-9]{3}[\/\-][0-1]?[0-9][\/\-][0-3]?[0-9]\b/',
        '/\bDOB\b/i',
        '/\bDATE OF BIRTH\b/i'
    ];

    foreach ($dobPatterns as $pattern) {

        if (preg_match($pattern, $text)) {

            $dobFound = true;
            break;
        }
    }

    if ($dobFound) {

        $score += 8;

        $reasons[] =
            'Date-of-birth information detected.';

    } else {

        $warnings[] =
            'Date-of-birth information not detected.';
    }


    /*
    =========================================================
    5. GENDER CHECK
    =========================================================
    */

    $genderFound = false;

    $genderWords = [
        'MALE',
        'FEMALE',
        'TRANSGENDER',
        'पुरुष',
        'महिला'
    ];

    foreach ($genderWords as $word) {

        if (
            stripos($upper, strtoupper($word)) !== false ||
            strpos($text, $word) !== false
        ) {

            $genderFound = true;
            break;
        }
    }

    if ($genderFound) {

        $score += 5;

        $reasons[] =
            'Demographic indicator detected.';
    }


    /*
    =========================================================
    6. PIN CODE
    =========================================================
    */

    $pinFound = false;

    if (preg_match('/\b[1-9][0-9]{5}\b/', $text)) {

        $pinFound = true;

        $score += 5;

        $reasons[] =
            'Six-digit postal code pattern detected.';
    }


    /*
    =========================================================
    7. NAME DETECTION
    =========================================================
    */

    $detectedName = '';

    $lines = preg_split(
        '/\r\n|\r|\n/',
        $ocr
    );

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line === '') {
            continue;
        }

        $u = strtoupper($line);

        if (
            strpos($u, 'GOVERNMENT') !== false ||
            strpos($u, 'GOVT') !== false ||
            strpos($u, 'INDIA') !== false ||
            strpos($u, 'AADHAAR') !== false ||
            strpos($u, 'AADHAR') !== false ||
            strpos($line, 'भारत') !== false ||
            strpos($line, 'आधार') !== false
        ) {
            continue;
        }

        if (preg_match('/[0-9]/', $line)) {
            continue;
        }

        if (
            strlen($line) >= 3 &&
            strlen($line) <= 60
        ) {

            $detectedName = $line;
            break;
        }
    }

    if ($detectedName === '' && $jsName !== '') {
        $detectedName = $jsName;
    }

    if ($detectedName !== '') {

        $score += 7;

        $reasons[] =
            'Possible document-holder name detected.';

    } else {

        $warnings[] =
            'Holder name could not be confidently detected.';
    }


    /*
    =========================================================
    8. OCR QUALITY
    =========================================================
    */

    $length = strlen(trim($ocr));

    if ($length >= 150) {

        $score += 10;

        $reasons[] =
            'OCR extracted sufficient document information.';

    } elseif ($length >= 80) {

        $score += 6;

        $reasons[] =
            'OCR extracted moderate document information.';

    } elseif ($length >= 40) {

        $score += 2;

        $warnings[] =
            'OCR output is limited.';

    } else {

        $warnings[] =
            'OCR output is too short.';
    }


    /*
    =========================================================
    9. SUSPICIOUS WORDS
    =========================================================
    */

    $suspiciousWords = [
        'DUPLICATE',
        'SAMPLE',
        'SPECIMEN',
        'FAKE',
        'DEMO',
        'NOT VALID',
        'INVALID',
        'FOR DEMO',
        'SAMPLE COPY'
    ];

    $suspiciousFound = [];

    foreach ($suspiciousWords as $word) {

        if (stripos($upper, $word) !== false) {

            $suspiciousFound[] = $word;
        }
    }


    /*
    =========================================================
    10. SUSPICIOUS PENALTY
    =========================================================
    */

    if (count($suspiciousFound) > 0) {

        $score -= 55;

        $reasons[] =
            'Suspicious document indicator detected: ' .
            implode(', ', $suspiciousFound);
    }


    /*
    =========================================================
    SCORE LIMIT
    =========================================================
    */

    if ($score < 0) {
        $score = 0;
    }

    if ($score > 100) {
        $score = 100;
    }


    /*
    =========================================================
    FINAL DECISION
    =========================================================

    APPROVED:
    Strong document-screening evidence.

    MANUAL REVIEW:
    Some information exists but evidence is incomplete.

    REJECTED:
    Strong suspicious indicators or extremely weak evidence.
    */

    if (count($suspiciousFound) > 0) {

        $status = 'REJECTED';

    } elseif (
        $aadhaar !== '' &&
        $aadhaarKeyword &&
        $govKeyword &&
        $score >= 65
    ) {

        $status = 'APPROVED';

    } elseif ($score >= 40) {

        $status = 'MANUAL REVIEW';

    } else {

        $status = 'REJECTED';
    }


    /*
    =========================================================
    MASK AADHAAR
    =========================================================
    */

    $maskedAadhaar = 'XXXX XXXX XXXX';

    if ($aadhaar !== '') {

        $parts = explode(' ', $aadhaar);

        if (count($parts) === 3) {

            $maskedAadhaar =
                'XXXX XXXX ' . $parts[2];
        }
    }


    /*
    =========================================================
    SEND RESULT
    =========================================================
    */

    $params = [
        'status'  => $status,
        'score'   => $score,
        'name'    => $detectedName,
        'aadhaar' => $maskedAadhaar,
        'reason'  => implode('|', $reasons),
        'warning' => implode('|', $warnings)
    ];

    header(
        'Location: verification_result.php?' .
        http_build_query($params)
    );

    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
Enterprise DigiVerify - Aadhaar Verification
</title>

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
        radial-gradient(
            circle at top left,
            #12345b,
            transparent 40%
        ),
        radial-gradient(
            circle at bottom right,
            #063b45,
            transparent 40%
        ),
        #050b16;

    color: white;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 25px;
}

.container {

    width: 100%;

    max-width: 720px;

    background: rgba(8,20,38,.97);

    border: 1px solid #1d6f91;

    border-radius: 22px;

    padding: 38px;

    box-shadow:
        0 0 50px
        rgba(0,190,255,.16);
}

.logo {

    text-align: center;

    color: #42d9ff;

    font-size: 31px;

    font-weight: bold;
}

.subtitle {

    text-align: center;

    color: #9fb5c9;

    margin: 10px 0 30px;
}

.upload-box {

    border: 2px dashed #2386a8;

    border-radius: 16px;

    padding: 35px;
}

input[type=file] {

    width: 100%;

    padding: 15px;

    background: #0c1a2c;

    color: white;

    border: 1px solid #31556e;

    border-radius: 10px;
}

button {

    width: 100%;

    margin-top: 20px;

    padding: 16px;

    border: 0;

    border-radius: 10px;

    background:
        linear-gradient(
            90deg,
            #00a8e8,
            #00d4aa
        );

    color: #001018;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;
}

button:hover {

    transform: translateY(-1px);

    opacity: .92;
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

    padding: 15px;

    background: #0b1727;

    border-radius: 10px;

    color: #9fb1c0;

    font-size: 13px;

    line-height: 1.6;

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

        <button
            type="button"
            onclick="startVerification()"
        >
            🔍 ANALYZE & VERIFY DOCUMENT
        </button>

    </div>

    <div id="loading-box">

        <div id="status-text">
            Initializing OCR...
        </div>

    </div>

    <div class="notice">

        <strong>AI Screening Engine</strong><br>

        OCR • Identity Pattern • Government Indicator •
        DOB • Demographic Data • PIN Code •
        Suspicious Document Detection

        <br><br>

        Result represents DigiVerify project-level
        screening and is not official UIDAI authentication.

    </div>


    <form
        method="POST"
        id="main-form"
    >

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

    const input =
        document.getElementById('file-input');

    if (
        !input.files ||
        input.files.length === 0
    ) {

        alert(
            'Please select an Aadhaar document image.'
        );

        return;
    }

    const file = input.files[0];

    document.getElementById(
        'loading-box'
    ).style.display = 'block';

    const status =
        document.getElementById(
            'status-text'
        );

    try {

        status.textContent =
            'Loading AI OCR engine...';

        const result =
            await Tesseract.recognize(
                file,
                'eng',
                {
                    logger: function(message) {

                        if (
                            message.status ===
                            'recognizing text'
                        ) {

                            const progress =
                                Math.round(
                                    message.progress * 100
                                );

                            status.textContent =
                                'OCR Analysis: ' +
                                progress +
                                '%';
                        }
                    }
                }
            );


        const text =
            result.data.text || '';


        document.getElementById(
            'ocr-hidden-input'
        ).value = text;


        /*
        -------------------------------------------
        Possible name extraction
        -------------------------------------------
        */

        let possibleName = '';

        const lines =
            text.split(/\r?\n/);

        for (
            let i = 0;
            i < lines.length;
            i++
        ) {

            const current =
                lines[i]
                .trim()
                .toUpperCase();

            if (
                current.includes('GOVERNMENT') ||
                current.includes('INDIA')
            ) {

                if (lines[i + 1]) {

                    const candidate =
                        lines[i + 1].trim();

                    if (
                        candidate.length >= 3 &&
                        candidate.length <= 60 &&
                        !/[0-9]/.test(candidate)
                    ) {

                        possibleName =
                            candidate;

                        break;
                    }
                }
            }
        }


        document.getElementById(
            'js-name-input'
        ).value = possibleName;


        status.textContent =
            'OCR completed. Running AI screening...';


        setTimeout(function() {

            document
                .getElementById('main-form')
                .submit();

        }, 600);


    } catch (error) {

        console.error(error);

        status.textContent =
            'OCR processing failed.';

        alert(
            'Could not analyze this image. Please use a clear JPG or PNG image.'
        );
    }
}

</script>

</body>

</html>
