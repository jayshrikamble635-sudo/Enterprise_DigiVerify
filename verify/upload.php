<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocr_text'])) {
    $ocr = trim($_POST['ocr_text']);
    $jsName = trim($_POST['js_name'] ?? '');
    $qrStatus = strtoupper(trim($_POST['qr_status'] ?? 'NOT_DETECTED'));
    $qrData = trim($_POST['qr_data'] ?? '');

    $score = 0;
    $reasons = [];
    $warnings = [];

    /* Normalize OCR while preserving line breaks for name detection. */
    $text = trim(preg_replace('/[ \t]+/', ' ', $ocr));
    $upper = strtoupper($text);
    $compact = preg_replace('/[^0-9]/', '', $text);

    /* =====================================================
       1. AADHAAR NUMBER DETECTION
       ===================================================== */
    $aadhaar = '';
    $aadhaarFound = false;

    $patterns = [
        '/\b([0-9]{4})[\s\-]+([0-9]{4})[\s\-]+([0-9]{4})\b/',
        '/\b([0-9]{4})([0-9]{4})([0-9]{4})\b/'
    ];

    foreach ($patterns as $p) {
        if (preg_match($p, $text, $m)) {
            $aadhaar = "$m[1] $m[2] $m[3]";
            $aadhaarFound = true;
            break;
        }
    }

    /* OCR sometimes inserts spaces incorrectly; search digit stream. */
    if (!$aadhaarFound && preg_match_all('/(?<![0-9])([0-9][0-9\s\-]{10,18}[0-9])(?![0-9])/', $text, $matches)) {
        foreach ($matches[1] as $candidate) {
            $digits = preg_replace('/[^0-9]/', '', $candidate);
            if (strlen($digits) === 12) {
                $aadhaar = substr($digits,0,4).' '.substr($digits,4,4).' '.substr($digits,8,4);
                $aadhaarFound = true;
                break;
            }
        }
    }

    if ($aadhaarFound) {
        $score += 35;
        $reasons[] = '12-digit Aadhaar number pattern detected.';
    } else {
        $warnings[] = 'Aadhaar number was not clearly detected by OCR.';
    }

    /* =====================================================
       2. AADHAAR KEYWORD
       ===================================================== */
    $aadhaarKeyword = (
        stripos($upper, 'AADHAAR') !== false ||
        stripos($upper, 'AADHAR') !== false ||
        strpos($text, 'आधार') !== false
    );

    if ($aadhaarKeyword) {
        $score += 20;
        $reasons[] = 'Aadhaar identity indicator detected.';
    } else {
        $warnings[] = 'Aadhaar keyword was not clearly detected.';
    }

    /* =====================================================
       3. GOVERNMENT / INDIA INDICATOR
       ===================================================== */
    $gov = false;
    foreach ([
        'GOVERNMENT OF INDIA',
        'GOVT OF INDIA',
        'GOVERNMENT',
        'GOVT.',
        'INDIA',
        'भारत सरकार',
        'भारत'
    ] as $w) {
        if (stripos($upper, strtoupper($w)) !== false || strpos($text, $w) !== false) {
            $gov = true;
            break;
        }
    }

    if ($gov) {
        $score += 10;
        $reasons[] = 'Government/India indicator detected.';
    } else {
        $warnings[] = 'Government/India indicator was not clearly detected.';
    }

    /* =====================================================
       4. DOB / YEAR OF BIRTH
       ===================================================== */
    $dob = false;
    foreach ([
        '/\b[0-3]?[0-9][\/\-][0-1]?[0-9][\/\-][12][0-9]{3}\b/',
        '/\b[12][0-9]{3}[\/\-][0-1]?[0-9][\/\-][0-3]?[0-9]\b/',
        '/\bDOB\b/i',
        '/\bDATE OF BIRTH\b/i',
        '/\bYEAR OF BIRTH\b/i',
        '/\bYOB\b/i'
    ] as $p) {
        if (preg_match($p, $text)) {
            $dob = true;
            break;
        }
    }

    if ($dob) {
        $score += 8;
        $reasons[] = 'Date-of-birth information detected.';
    } else {
        $warnings[] = 'Date-of-birth information was not detected.';
    }

    /* =====================================================
       5. GENDER
       ===================================================== */
    $gender = false;
    foreach (['MALE','FEMALE','TRANSGENDER','पुरुष','महिला'] as $w) {
        if (stripos($upper, strtoupper($w)) !== false || strpos($text, $w) !== false) {
            $gender = true;
            break;
        }
    }

    if ($gender) {
        $score += 5;
        $reasons[] = 'Demographic gender information detected.';
    } else {
        $warnings[] = 'Gender information was not clearly detected.';
    }

    /* =====================================================
       6. PIN CODE / ADDRESS EVIDENCE
       ===================================================== */
    $pin = preg_match('/\b[1-9][0-9]{5}\b/', $text) === 1;
    if ($pin) {
        $score += 5;
        $reasons[] = 'Six-digit postal PIN pattern detected.';
    } else {
        $warnings[] = 'Six-digit PIN code was not detected.';
    }

    /* =====================================================
       7. NAME DETECTION
       ===================================================== */
    $name = '';
    $blocked = [
        'GOVERNMENT','GOVT','INDIA','AADHAAR','AADHAR','DATE OF BIRTH','DOB',
        'YEAR OF BIRTH','YOB','MALE','FEMALE','TRANSGENDER','ADDRESS','PIN','VID',
        'IDENTIFICATION','UNIQUE','AUTHORITY','ENROLMENT','ENROLLMENT'
    ];

    foreach (preg_split('/\r\n|\r|\n/', $ocr) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $u = strtoupper($line);

        $skip = false;
        foreach ($blocked as $word) {
            if (strpos($u, $word) !== false) { $skip = true; break; }
        }
        if ($skip || preg_match('/[0-9]/', $line)) continue;

        $clean = trim(preg_replace('/\s+/', ' ', preg_replace('/[^A-Za-zÀ-ÿ .\'\-]/', '', $line)));
        $wordCount = preg_match_all('/[A-Za-z]{2,}/', $clean, $tmp);

        if (strlen($clean) >= 3 && strlen($clean) <= 60 && $wordCount >= 1) {
            $name = $clean;
            break;
        }
    }

    if ($name === '' && $jsName !== '') {
        $clean = trim(preg_replace('/\s+/', ' ', preg_replace('/[^A-Za-zÀ-ÿ .\'\-]/', '', $jsName)));
        if (strlen($clean) >= 3 && strlen($clean) <= 60) $name = $clean;
    }

    if ($name !== '') {
        $score += 7;
        $reasons[] = 'Possible document-holder name detected.';
    } else {
        $warnings[] = 'Holder name could not be confidently detected.';
    }

    /* =====================================================
       8. OCR QUALITY
       ===================================================== */
    $length = strlen(trim($ocr));
    if ($length >= 180) {
        $score += 10;
        $reasons[] = 'Multiple OCR passes extracted substantial document information.';
    } elseif ($length >= 100) {
        $score += 7;
        $reasons[] = 'OCR extracted sufficient document information.';
    } elseif ($length >= 50) {
        $score += 3;
        $warnings[] = 'OCR output is limited.';
    } else {
        $warnings[] = 'OCR output is too short for confident screening.';
    }

    /* =====================================================
       9. QR DETECTION
       ===================================================== */
    if ($qrStatus === 'DETECTED' && $qrData !== '') {
        $qrVerification = 'DETECTED';
        $score += 15;
        $reasons[] = 'QR code was detected and decoded from the uploaded image.';
    } elseif ($qrStatus === 'DETECTED') {
        $qrVerification = 'DETECTED';
        $score += 12;
        $reasons[] = 'QR code was detected in the uploaded image.';
    } else {
        $qrVerification = 'NOT_DETECTED';
        $warnings[] = 'No readable QR code was detected.';
    }

    /* =====================================================
       10. SUSPICIOUS / SAMPLE / FAKE INDICATORS
       ===================================================== */
    $susp = [];
    foreach ([
        'DUPLICATE','SAMPLE','SPECIMEN','FAKE','DEMO','NOT VALID','INVALID',
        'FOR DEMO','SAMPLE COPY','TEST COPY','PHOTOCOPY FOR DEMO'
    ] as $w) {
        if (stripos($upper, $w) !== false) $susp[] = $w;
    }

    if ($susp) {
        $score -= 60;
        $warnings[] = 'Suspicious document indicator detected: '.implode(', ', array_unique($susp));
    }

    $score = max(0, min(100, $score));

    /* =====================================================
       11. EVIDENCE COUNT / AI SCREENING
       ===================================================== */
    $evidence = 0;
    foreach ([$aadhaarFound,$aadhaarKeyword,$gov,$dob,$gender,($name !== ''),($qrVerification === 'DETECTED')] as $flag) {
        if ($flag) $evidence++;
    }

    if ($susp) {
        $status = 'REJECTED';
        $aiResult = 'SUSPICIOUS';
        $aiReason = 'Suspicious or demo/fake-document indicators were detected in the document data.';
        $reasons[] = 'Document was rejected because suspicious indicators were detected.';
    } elseif (
        ($aadhaarFound && $gov && $name !== '') ||
        ($aadhaarKeyword && $gov && $dob && $name !== '') ||
        ($gov && $dob && $gender && $name !== '' && $length >= 80) ||
        ($qrVerification === 'DETECTED' && $gov && ($name !== '' || $dob || $gender))
    ) {
        $status = 'APPROVED';
        $aiResult = 'REAL-LIKE';
        $aiReason = 'Multiple expected identity-document characteristics were detected. This is project-level screening, not official government authentication.';
        $reasons[] = 'Document contains sufficient identity evidence for project-level screening.';
    } else {
        $status = 'REJECTED';
        $aiResult = 'SUSPICIOUS';
        $aiReason = 'Required identity evidence was insufficient for confident project-level screening.';
        $reasons[] = 'Required identity evidence was insufficient for approval.';
    }

    if ($status === 'REJECTED') {
        $warnings[] = 'Document did not meet the minimum DigiVerify screening criteria.';
    }

    /* =====================================================
       12. KYC / TRANSACTION
       ===================================================== */
    $kycStatus = 'NOT_AVAILABLE';
    $transaction = 'NOT_AVAILABLE';
    $warnings[] = 'Official UIDAI KYC was not performed because no authorized UIDAI KUA/AUA/ASA integration is configured.';

    /* Mask Aadhaar. */
    $masked = 'XXXX XXXX XXXX';
    if ($aadhaar !== '') {
        $p = preg_split('/\s+/', $aadhaar);
        if (count($p) === 3) $masked = 'XXXX XXXX '.$p[2];
    }

    $params = [
        'status' => $status,
        'score' => $score,
        'name' => $name,
        'aadhaar' => $masked,
        'ai_result' => $aiResult,
        'ai_reason' => $aiReason,
        'evidence_count' => $evidence,
        'qr_status' => $qrVerification,
        'qr_data' => $qrData,
        'kyc_status' => $kycStatus,
        'transaction_id' => $transaction,
        'reason' => implode('|', array_unique($reasons)),
        'warning' => implode('|', array_unique($warnings))
    ];

    header('Location: verification_result.php?'.http_build_query($params));
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Enterprise DigiVerify - Document Verification</title>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<style>
*{box-sizing:border-box}body{margin:0;min-height:100vh;font-family:Arial,Helvetica,sans-serif;color:#fff;background:radial-gradient(circle at top left,#12345b,transparent 40%),radial-gradient(circle at bottom right,#063b45,transparent 40%),#050b16;display:flex;justify-content:center;align-items:center;padding:25px}.container{width:100%;max-width:760px;padding:40px;background:linear-gradient(145deg,rgba(8,25,45,.98),rgba(4,13,27,.98));border:2px solid #195477;border-radius:22px;box-shadow:0 0 50px rgba(0,190,255,.16)}.logo{text-align:center;color:#42d9ff;font-size:31px;font-weight:1000}.subtitle{margin:10px 0 30px;text-align:center;color:#9fb5c9;font-size:14px;font-weight:700}.upload-box{padding:35px;background:rgba(5,18,34,.8);border:2px dashed #2386a8;border-radius:16px}input[type=file]{width:100%;padding:15px;background:#0c1a2c;color:white;border:2px solid #31556e;border-radius:10px;cursor:pointer}input[type=file]::file-selector-button{margin-right:12px;padding:9px 14px;border:0;border-radius:7px;background:#00b8e6;color:#00131c;font-weight:800;cursor:pointer}button{width:100%;margin-top:20px;padding:17px;border:2px solid #00b8dc;border-radius:10px;background:linear-gradient(90deg,#00a8e8,#00d4aa);color:#001018;font-size:17px;font-weight:1000;cursor:pointer;box-shadow:0 0 20px rgba(0,212,255,.18)}button:disabled{opacity:.6;cursor:wait}#loading-box{display:none;margin-top:25px;padding:22px;border-radius:12px;background:#0b1b2e;border:2px solid #24728e}#status-text{color:#42d9ff;text-align:center;font-size:14px;font-weight:800}.notice{margin-top:25px;padding:17px;background:rgba(11,23,39,.95);border:1px solid #254b62;border-radius:10px;color:#9fb1c0;font-size:12px;line-height:1.7;text-align:center}@media(max-width:600px){body{padding:12px}.container{padding:22px 16px}.logo{font-size:25px}.upload-box{padding:22px 15px}}
</style>
</head>
<body>
<div class="container">
<div class="logo">Enterprise DigiVerify</div>
<div class="subtitle">AI-Assisted Aadhaar Document Screening</div>
<div class="upload-box">
<input type="file" id="file-input" accept="image/jpeg,image/png,image/webp">
<button type="button" id="verify-button" onclick="startVerification()">🔍 ANALYZE & VERIFY DOCUMENT</button>
</div>
<div id="loading-box"><div id="status-text">Initializing scanner...</div></div>
<div class="notice"><strong>Enhanced AI Screening Engine</strong><br>Multi-Pass OCR • Aadhaar Pattern • Government Indicator • Name • DOB • Gender • PIN • QR Multi-Scan • Suspicious Document Detection • Evidence Scoring<br><br>QR detection means the QR was readable. It does not by itself prove official UIDAI authenticity.</div>
<form method="POST" id="main-form">
<input type="hidden" name="ocr_text" id="ocr-hidden-input">
<input type="hidden" name="js_name" id="js-name-input">
<input type="hidden" name="qr_status" id="qr-status-input">
<input type="hidden" name="qr_data" id="qr-data-input">
</form>
</div>
<script>
function sleep(ms){return new Promise(r=>setTimeout(r,ms));}

function canvasFromImage(img, scale=1, mode='normal', angle=0){
    const w=Math.max(1,Math.round(img.naturalWidth*scale));
    const h=Math.max(1,Math.round(img.naturalHeight*scale));
    const swap=Math.abs(angle)%180===90;
    const c=document.createElement('canvas');
    c.width=swap?h:w; c.height=swap?w:h;
    const ctx=c.getContext('2d',{willReadFrequently:true});
    ctx.save();
    ctx.translate(c.width/2,c.height/2);
    ctx.rotate(angle*Math.PI/180);
    ctx.drawImage(img,-w/2,-h/2,w,h);
    ctx.restore();
    if(mode!=='normal'){
        const im=ctx.getImageData(0,0,c.width,c.height), d=im.data;
        for(let i=0;i<d.length;i+=4){
            const g=0.299*d[i]+0.587*d[i+1]+0.114*d[i+2];
            let v=g;
            if(mode==='contrast') v=Math.max(0,Math.min(255,(g-128)*1.65+128));
            if(mode==='binary') v=g>150?255:0;
            d[i]=d[i+1]=d[i+2]=v;
        }
        ctx.putImageData(im,0,0);
    }
    return c;
}

async function loadImage(file){
    return new Promise((resolve,reject)=>{
        const url=URL.createObjectURL(file), img=new Image();
        img.onload=()=>{URL.revokeObjectURL(url);resolve(img)};
        img.onerror=reject; img.src=url;
    });
}

async function scanQR(img){
    let best='', attempts=0;
    const variants=[
        [1,'normal',0],[1.5,'normal',0],[2,'normal',0],
        [1.5,'contrast',0],[2,'contrast',0],
        [1.5,'normal',90],[1.5,'normal',-90],
        [2,'normal',90],[2,'normal',-90],
        [2,'binary',0]
    ];
    for(const [scale,mode,angle] of variants){
        attempts++;
        try{
            const c=canvasFromImage(img,Math.min(scale,3),mode,angle);
            const ctx=c.getContext('2d',{willReadFrequently:true});
            const data=ctx.getImageData(0,0,c.width,c.height);
            if(typeof jsQR==='function'){
                const code=jsQR(data.data,data.width,data.height,{inversionAttempts:'attemptBoth'});
                if(code && code.data){return {status:'DETECTED',data:code.data,attempts};}
            }
        }catch(e){console.warn('QR variant failed',e)}
        await sleep(10);
    }
    return {status:'NOT_DETECTED',data:'',attempts};
}

async function multiPassOCR(img,status){
    const passes=[
        [1,'normal','Standard OCR scan'],
        [1.6,'contrast','Enhanced contrast OCR scan'],
        [2,'normal','High-resolution OCR scan']
    ];
    let outputs=[];
    for(const [scale,mode,label] of passes){
        status.textContent=label+'...';
        try{
            const canvas=canvasFromImage(img,scale,mode,0);
            const result=await Tesseract.recognize(canvas,'eng',{logger:m=>{
                if(m.status==='recognizing text') status.textContent=label+' '+Math.round(m.progress*100)+'%';
            }});
            const t=result?.data?.text||'';
            if(t.trim()) outputs.push(t.trim());
        }catch(e){console.warn('OCR pass failed',e)}
    }
    const unique=[...new Set(outputs)];
    return unique.join('\n\n--- OCR PASS ---\n\n');
}

function detectName(text){
    const blocked=['GOVERNMENT','GOVT','INDIA','AADHAAR','AADHAR','DATE OF BIRTH','DOB','YEAR OF BIRTH','YOB','MALE','FEMALE','TRANSGENDER','ADDRESS','PIN','VID','IDENTIFICATION','UNIQUE','AUTHORITY','ENROLMENT','ENROLLMENT'];
    for(const line of text.split(/\r\n|\r|\n/)){
        const candidate=line.trim(); if(!candidate||/[0-9]/.test(candidate)) continue;
        const u=candidate.toUpperCase(); if(blocked.some(x=>u.includes(x))) continue;
        const clean=candidate.replace(/[^A-Za-zÀ-ÿ .'\-]/g,'').replace(/\s+/g,' ').trim();
        const words=(clean.match(/[A-Za-z]{2,}/g)||[]).length;
        if(clean.length>=3&&clean.length<=60&&words>=1)return clean;
    }
    return '';
}

async function startVerification(){
    const input=document.getElementById('file-input'),button=document.getElementById('verify-button'),loading=document.getElementById('loading-box'),status=document.getElementById('status-text');
    if(!input.files?.length){alert('Please select an Aadhaar document image.');return;}
    const file=input.files[0];
    if(!['image/jpeg','image/png','image/webp'].includes(file.type)){alert('Please upload JPG, PNG or WEBP image.');return;}
    if(file.size>10*1024*1024){alert('Image size must be less than 10 MB.');return;}
    button.disabled=true; loading.style.display='block';
    try{
        const img=await loadImage(file);
        status.textContent='Scanning QR using multiple image passes...';
        const qr=await scanQR(img);
        document.getElementById('qr-status-input').value=qr.status;
        document.getElementById('qr-data-input').value=qr.data;

        status.textContent=qr.status==='DETECTED'?'✓ QR detected. Starting multi-pass OCR...':'QR not detected. Starting enhanced OCR...';
        const text=await multiPassOCR(img,status);
        if(!text.trim())throw new Error('No OCR text detected.');
        document.getElementById('ocr-hidden-input').value=text;
        const possibleName=detectName(text);
        document.getElementById('js-name-input').value=possibleName;
        status.textContent='Combining scan evidence and generating confidence score...';
        await sleep(250);
        document.getElementById('main-form').submit();
    }catch(e){console.error(e);status.textContent='Verification processing failed.';alert('Could not analyze this image. Please use a clear JPG, PNG or WEBP image.');button.disabled=false;}
}
</script>
</body>
</html>
