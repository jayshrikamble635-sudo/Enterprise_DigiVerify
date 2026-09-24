<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocr_text'])) {

    $ocr = trim($_POST['ocr_text']);
    $jsName = trim($_POST['js_name'] ?? '');

    $qrStatus = strtoupper(
        trim($_POST['qr_status'] ?? 'NOT_DETECTED')
    );

    $qrData = trim(
        $_POST['qr_data'] ?? ''
    );

    /*
    =========================================================
    DIGIVERIFY INTELLIGENCE ENGINE
    =========================================================
    */

    $score = 0;

    $reasons = [];
    $warnings = [];

    $text = trim(
        preg_replace('/[ \t]+/', ' ', $ocr)
    );

    $upper = strtoupper($text);

    /*
    =========================================================
    1. SUSPICIOUS / FAKE / DUPLICATE INDICATORS
    =========================================================
    */

    $suspiciousIndicators = [];

    $suspiciousWords = [
        'DUPLICATE COPY',
        'DUPLICATE',
        'FAKE',
        'SPECIMEN',
        'SAMPLE COPY',
        'SAMPLE',
        'DEMO COPY',
        'DEMO',
        'NOT VALID',
        'INVALID',
        'FOR DEMO',
        'TEST COPY'
    ];

    foreach ($suspiciousWords as $word) {

        if (
            stripos($upper, $word) !== false
        ) {

            $suspiciousIndicators[] = $word;
        }
    }

    /*
    =========================================================
    2. AADHAAR NUMBER
    =========================================================
    */

    $aadhaar = '';

    $aadhaarPatterns = [

        '/\b([0-9]{4})[\s\-]+([0-9]{4})[\s\-]+([0-9]{4})\b/',

        '/\b([0-9]{4})([0-9]{4})([0-9]{4})\b/'
    ];

    foreach ($aadhaarPatterns as $pattern) {

        if (
            preg_match(
                $pattern,
                $text,
                $match
            )
        ) {

            $aadhaar =
                $match[1] . ' ' .
                $match[2] . ' ' .
                $match[3];

            break;
        }
    }

    if ($aadhaar !== '') {

        $score += 25;

        $reasons[] =
            '12-digit Aadhaar number pattern detected.';

    } else {

        $warnings[] =
            'Aadhaar number was not clearly detected.';
    }

    /*
    =========================================================
    3. AADHAAR KEYWORD
    =========================================================
    */

    $aadhaarKeyword =
        stripos($upper, 'AADHAAR') !== false ||
        stripos($upper, 'AADHAR') !== false ||
        strpos($text, 'आधार') !== false;

    if ($aadhaarKeyword) {

        $score += 15;

        $reasons[] =
            'Aadhaar identity indicator detected.';

    } else {

        $warnings[] =
            'Aadhaar identity keyword was not clearly detected.';
    }

    /*
    =========================================================
    4. GOVERNMENT OF INDIA
    =========================================================
    */

    $governmentDetected = false;

    $governmentIndicators = [

        'GOVERNMENT OF INDIA',
        'GOVT OF INDIA',
        'GOVERNMENT',
        'GOVT.',
        'INDIA',
        'भारत सरकार',
        'भारत'
    ];

    foreach (
        $governmentIndicators
        as $indicator
    ) {

        if (
            stripos(
                $upper,
                strtoupper($indicator)
            ) !== false
            ||
            strpos(
                $text,
                $indicator
            ) !== false
        ) {

            $governmentDetected = true;
            break;
        }
    }

    if ($governmentDetected) {

        $score += 10;

        $reasons[] =
            'Government of India indicator detected.';

    } else {

        $warnings[] =
            'Government/India indicator was not clearly detected.';
    }

    /*
    =========================================================
    5. DOB
    =========================================================
    */

    $dobDetected = false;

    $dobPatterns = [

        '/\b[0-3]?[0-9][\/\-][0-1]?[0-9][\/\-][12][0-9]{3}\b/',

        '/\b[12][0-9]{3}[\/\-][0-1]?[0-9][\/\-][0-3]?[0-9]\b/',

        '/\bDOB\b/i',

        '/\bDATE OF BIRTH\b/i',

        '/\bYEAR OF BIRTH\b/i'
    ];

    foreach ($dobPatterns as $pattern) {

        if (
            preg_match(
                $pattern,
                $text
            )
        ) {

            $dobDetected = true;
            break;
        }
    }

    if ($dobDetected) {

        $score += 10;

        $reasons[] =
            'Date-of-birth information detected.';

    } else {

        $warnings[] =
            'Date-of-birth information was not detected.';
    }

    /*
    =========================================================
    6. GENDER
    =========================================================
    */

    $genderDetected = false;

    $genderWords = [

        'MALE',
        'FEMALE',
        'TRANSGENDER',
        'पुरुष',
        'महिला'
    ];

    foreach ($genderWords as $gender) {

        if (
            stripos(
                $upper,
                strtoupper($gender)
            ) !== false
            ||
            strpos(
                $text,
                $gender
            ) !== false
        ) {

            $genderDetected = true;
            break;
        }
    }

    if ($genderDetected) {

        $score += 8;

        $reasons[] =
            'Demographic gender information detected.';
    }

    /*
    =========================================================
    7. PIN CODE
    =========================================================
    */

    $pinDetected =
        preg_match(
            '/\b[1-9][0-9]{5}\b/',
            $text
        );

    if ($pinDetected) {

        $score += 5;

        $reasons[] =
            'Six-digit postal code pattern detected.';
    }

    /*
    =========================================================
    8. HOLDER NAME
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

        $lineUpper =
            strtoupper($line);

        /*
        Ignore known document headings
        */

        if (
            strpos($lineUpper, 'GOVERNMENT') !== false ||
            strpos($lineUpper, 'GOVT') !== false ||
            strpos($lineUpper, 'INDIA') !== false ||
            strpos($lineUpper, 'AADHAAR') !== false ||
            strpos($lineUpper, 'AADHAR') !== false ||
            strpos($lineUpper, 'DATE OF BIRTH') !== false ||
            strpos($lineUpper, 'DOB') !== false ||
            strpos($lineUpper, 'YEAR OF BIRTH') !== false ||
            strpos($lineUpper, 'MALE') !== false ||
            strpos($lineUpper, 'FEMALE') !== false ||
            strpos($line, 'भारत') !== false ||
            strpos($line, 'आधार') !== false
        ) {

            continue;
        }

        /*
        Ignore lines containing numbers
        */

        if (
            preg_match(
                '/[0-9]/',
                $line
            )
        ) {

            continue;
        }

        $clean =
            trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    preg_replace(
                        '/[^A-Za-zÀ-ÿ .\'\-]/',
                        '',
                        $line
                    )
                )
            );

        if (
            strlen($clean) >= 3 &&
            strlen($clean) <= 60 &&
            preg_match(
                '/[A-Za-z]{2,}/',
                $clean
            )
        ) {

            $detectedName = $clean;

            break;
        }
    }

    /*
    JS name fallback
    */

    if (
        $detectedName === '' &&
        $jsName !== ''
    ) {

        $clean =
            trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    preg_replace(
                        '/[^A-Za-zÀ-ÿ .\'\-]/',
                        '',
                        $jsName
                    )
                )
            );

        if (
            strlen($clean) >= 3 &&
            strlen($clean) <= 60
        ) {

            $detectedName = $clean;
        }
    }

    if ($detectedName !== '') {

        $score += 10;

        $reasons[] =
            'Possible document-holder name detected.';

    } else {

        $warnings[] =
            'Document-holder name could not be confidently detected.';
    }

    /*
    =========================================================
    9. OCR QUALITY
    =========================================================
    */

    $ocrLength =
        strlen(
            trim($ocr)
        );

    if ($ocrLength >= 150) {

        $score += 12;

        $reasons[] =
            'OCR extracted sufficient document information.';

    } elseif ($ocrLength >= 80) {

        $score += 8;

        $reasons[] =
            'OCR extracted moderate document information.';

    } elseif ($ocrLength >= 40) {

        $score += 3;

        $warnings[] =
            'OCR output is limited.';

    } else {

        $warnings[] =
            'OCR output is too short.';
    }

    /*
    =========================================================
    10. QR DETECTION
    =========================================================
    */

    $qrDetected =
        ($qrStatus === 'DETECTED');

    if ($qrDetected) {

        $score += 10;

        $reasons[] =
            'QR code was detected in the uploaded document.';

    } else {

        $warnings[] =
            'No readable QR code was detected.';
    }

    /*
    =========================================================
    11. AI-LIKE DOCUMENT SCREENING
    =========================================================
    */

    $aiResult = 'REAL-LIKE';

    $aiReason = '';

    /*
    HARD SUSPICION
    */

    if (
        count($suspiciousIndicators) > 0
    ) {

        $aiResult = 'SUSPICIOUS';

        $aiReason =
            'Suspicious document indicator detected: ' .
            implode(
                ', ',
                $suspiciousIndicators
            );

        $score -= 40;

        $warnings[] =
            $aiReason;
    }

    /*
    =========================================================
    12. DOCUMENT EVIDENCE SCORE
    =========================================================
    */

    $evidenceCount = 0;

    if ($aadhaar !== '') {
        $evidenceCount++;
    }

    if ($aadhaarKeyword) {
        $evidenceCount++;
    }

    if ($governmentDetected) {
        $evidenceCount++;
    }

    if ($dobDetected) {
        $evidenceCount++;
    }

    if ($genderDetected) {
        $evidenceCount++;
    }

    if ($detectedName !== '') {
        $evidenceCount++;
    }

    if ($qrDetected) {
        $evidenceCount++;
    }

    /*
    =========================================================
    13. REAL-LIKE / SUSPICIOUS DECISION
    =========================================================
    */

    if (
        count($suspiciousIndicators) === 0
    ) {

        if (
            $evidenceCount >= 5
        ) {

            $aiResult = 'REAL-LIKE';

            $aiReason =
                'Multiple Aadhaar document characteristics were detected.';

        } elseif (
            $evidenceCount >= 3
        ) {

            $aiResult = 'REAL-LIKE';

            $aiReason =
                'The document contains several expected identity fields, although some evidence is incomplete.';

        } else {

            $aiResult = 'SUSPICIOUS';

            $aiReason =
                'Insufficient document characteristics were detected.';
        }
    }

    /*
    =========================================================
    14. FINAL SCORE
    =========================================================
    */

    $score =
        max(
            0,
            min(
                100,
                $score
            )
        );

    /*
    =========================================================
    15. FINAL APPROVED / REJECTED
    =========================================================
    */

    if (
        count($suspiciousIndicators) > 0
    ) {

        $status = 'REJECTED';

        $reasons[] =
            'Document contains an explicit suspicious/duplicate indicator.';

    } elseif (
        $evidenceCount >= 5
    ) {

        $status = 'APPROVED';

        $reasons[] =
            'Multiple document identity and demographic characteristics matched.';

    } elseif (
        $evidenceCount >= 3 &&
        $governmentDetected &&
        $detectedName !== ''
    ) {

        $status = 'APPROVED';

        $reasons[] =
            'Document contains sufficient identity evidence for project-level screening.';

    } else {

        $status = 'REJECTED';

        $reasons[] =
            'Required Aadhaar identity evidence was insufficient for approval.';
    }

    /*
    =========================================================
    16. REJECTED WARNING
    =========================================================
    */

    if (
        $status === 'REJECTED'
    ) {

        $warnings[] =
            'Document did not meet the minimum DigiVerify screening criteria.';
    }

    /*
    =========================================================
    17. MASK AADHAAR
    =========================================================
    */

    $maskedAadhaar =
        'XXXX XXXX XXXX';

    if (
        $aadhaar !== ''
    ) {

        $parts =
            preg_split(
                '/\s+/',
                $aadhaar
            );

        if (
            count($parts) === 3
        ) {

            $maskedAadhaar =
                'XXXX XXXX ' .
                $parts[2];
        }
    }

    /*
    =========================================================
    18. QR STATUS
    =========================================================
    
    IMPORTANT:
    Browser jsQR only detects/decodes QR.
    It does NOT validate UIDAI's digital signature.
    */

    if ($qrDetected) {

        $qrVerification =
            'DETECTED';

    } else {

        $qrVerification =
            'NOT_VERIFIED';
    }

    /*
    =========================================================
    19. KYC
    =========================================================
    */

    $kycStatus =
        'NOT_VERIFIED';

    $transaction =
        'NOT_AVAILABLE';

    /*
    =========================================================
    20. SEND RESULT
    =========================================================
    */

    $params = [

        'status' =>
            $status,

        'score' =>
            $score,

        'name' =>
            $detectedName,

        'aadhaar' =>
            $maskedAadhaar,

        'ai_result' =>
            $aiResult,

        'ai_reason' =>
            $aiReason,

        'evidence_count' =>
            $evidenceCount,

        'qr_status' =>
            $qrVerification,

        'qr_data' =>
            $qrData,

        'kyc_status' =>
            $kycStatus,

        'transaction_id' =>
            $transaction,

        'reason' =>
            implode(
                '|',
                $reasons
            ),

        'warning' =>
            implode(
                '|',
                $warnings
            )
    ];

    header(
        'Location: verification_result.php?' .
        http_build_query($params)
    );

    exit;
}

?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Enterprise DigiVerify - Document Verification</title><script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script><script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<style>
*{box-sizing:border-box}body{margin:0;min-height:100vh;font-family:Arial,Helvetica,sans-serif;color:#fff;background:radial-gradient(circle at top left,#12345b,transparent 40%),radial-gradient(circle at bottom right,#063b45,transparent 40%),#050b16;display:flex;justify-content:center;align-items:center;padding:25px}.container{width:100%;max-width:760px;padding:40px;background:linear-gradient(145deg,rgba(8,25,45,.98),rgba(4,13,27,.98));border:2px solid #195477;border-radius:22px;box-shadow:0 0 50px rgba(0,190,255,.16)}.logo{text-align:center;color:#42d9ff;font-size:31px;font-weight:1000}.subtitle{margin:10px 0 30px;text-align:center;color:#9fb5c9;font-size:14px;font-weight:700}.upload-box{padding:35px;background:rgba(5,18,34,.8);border:2px dashed #2386a8;border-radius:16px}input[type=file]{width:100%;padding:15px;background:#0c1a2c;color:white;border:2px solid #31556e;border-radius:10px;cursor:pointer}input[type=file]::file-selector-button{margin-right:12px;padding:9px 14px;border:0;border-radius:7px;background:#00b8e6;color:#00131c;font-weight:800;cursor:pointer}button{width:100%;margin-top:20px;padding:17px;border:2px solid #00b8dc;border-radius:10px;background:linear-gradient(90deg,#00a8e8,#00d4aa);color:#001018;font-size:17px;font-weight:1000;cursor:pointer;box-shadow:0 0 20px rgba(0,212,255,.18)}#loading-box{display:none;margin-top:25px;padding:22px;border-radius:12px;background:#0b1b2e;border:2px solid #24728e}#status-text{color:#42d9ff;text-align:center;font-size:14px;font-weight:800}.notice{margin-top:25px;padding:17px;background:rgba(11,23,39,.95);border:1px solid #254b62;border-radius:10px;color:#9fb1c0;font-size:12px;line-height:1.7;text-align:center}@media(max-width:600px){body{padding:12px}.container{padding:22px 16px}.logo{font-size:25px}.upload-box{padding:22px 15px}}
</style></head><body><div class="container"><div class="logo">Enterprise DigiVerify</div><div class="subtitle">AI-Assisted Aadhaar Document Screening</div><div class="upload-box"><input type="file" id="file-input" accept="image/jpeg,image/png,image/webp"><button type="button" id="verify-button" onclick="startVerification()">🔍 ANALYZE & VERIFY DOCUMENT</button></div><div id="loading-box"><div id="status-text">Initializing...</div></div><div class="notice"><strong>AI Screening Engine</strong><br>OCR • Aadhaar Pattern • Government Indicator • DOB • Demographic Data • PIN Code • QR Detection • Suspicious Document Detection<br><br>QR detection is not the same as official UIDAI signature verification. Online UIDAI KYC requires the appropriate authorized ecosystem.</div><form method="POST" id="main-form"><input type="hidden" name="ocr_text" id="ocr-hidden-input"><input type="hidden" name="js_name" id="js-name-input"><input type="hidden" name="qr_status" id="qr-status-input"><input type="hidden" name="qr_data" id="qr-data-input"></form></div>
<script>
async function startVerification(){const input=document.getElementById('file-input'),button=document.getElementById('verify-button'),loading=document.getElementById('loading-box'),status=document.getElementById('status-text');if(!input.files?.length){alert('Please select an Aadhaar document image.');return}const file=input.files[0];if(!['image/jpeg','image/png','image/webp'].includes(file.type)){alert('Please upload JPG, PNG or WEBP image.');return}if(file.size>10*1024*1024){alert('Image size must be less than 10 MB.');return}button.disabled=true;loading.style.display='block';
try{status.textContent='Scanning Secure QR code...';let qr='NOT_DETECTED',qrData='';try{const bmp=await createImageBitmap(file),c=document.createElement('canvas'),max=2200,s=Math.min(1,max/Math.max(bmp.width,bmp.height));c.width=Math.max(1,Math.round(bmp.width*s));c.height=Math.max(1,Math.round(bmp.height*s));const x=c.getContext('2d');x.drawImage(bmp,0,0,c.width,c.height);const img=x.getImageData(0,0,c.width,c.height),code=typeof jsQR==='function'?jsQR(img.data,img.width,img.height,{inversionAttempts:'attemptBoth'}):null;if(code){qr='DETECTED';qrData=code.data||''}}catch(e){console.warn('QR scan failed',e)}document.getElementById('qr-status-input').value=qr;document.getElementById('qr-data-input').value=qrData;
status.textContent='Loading OCR engine...';const result=await Tesseract.recognize(file,'eng',{logger:m=>{if(m.status==='recognizing text')status.textContent='OCR Analysis: '+Math.round(m.progress*100)+'%'}});const text=result?.data?.text||'';if(!text.trim())throw new Error('No OCR text detected.');document.getElementById('ocr-hidden-input').value=text;let possibleName='';for(const line of text.split(/\r\n|\r|\n/)){const candidate=line.trim(),u=candidate.toUpperCase();if(!candidate||/[0-9]/.test(candidate)||/GOVERNMENT|GOVT|INDIA|AADHAAR|AADHAR|DATE OF BIRTH|DOB|YEAR OF BIRTH|MALE|FEMALE/.test(u))continue;const clean=candidate.replace(/[^A-Za-zÀ-ÿ .'\-]/g,'').replace(/\s+/g,' ').trim();if(clean.length>=3&&clean.length<=60&&/[A-Za-z]{2,}/.test(clean)){possibleName=clean;break}}document.getElementById('js-name-input').value=possibleName;status.textContent=qr==='DETECTED'?'QR detected. Running document screening...':'QR not detected. Running document screening...';document.getElementById('main-form').submit()}catch(e){console.error(e);status.textContent='Verification processing failed.';alert('Could not analyze this image. Please use a clear JPG, PNG or WEBP image.');button.disabled=false}}
</script></body></html>
