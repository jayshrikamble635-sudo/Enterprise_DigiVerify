<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ocr_text'])) {
    $ocr=trim($_POST['ocr_text']);
    $jsName=trim($_POST['js_name']??'');
    $qrStatus=strtoupper(trim($_POST['qr_status']??'NOT_DETECTED'));
    $qrData=trim($_POST['qr_data']??'');
    $score=0;$reasons=[];$warnings=[];
    $text=trim(preg_replace('/[ \t]+/',' ',$ocr));
    $upper=strtoupper($text);

    $aadhaar='';
    foreach([
        '/\b([0-9]{4})[\s\-]+([0-9]{4})[\s\-]+([0-9]{4})\b/',
        '/\b([0-9]{4})([0-9]{4})([0-9]{4})\b/'
    ] as $p){if(preg_match($p,$text,$m)){ $aadhaar="$m[1] $m[2] $m[3]";break;}}
    if($aadhaar!==''){$score+=35;$reasons[]='12-digit Aadhaar number pattern detected.';}else{$warnings[]='Aadhaar number was not clearly detected.';}

    $aadhaarKeyword=(stripos($upper,'AADHAAR')!==false||stripos($upper,'AADHAR')!==false||strpos($text,'आधार')!==false);
    if($aadhaarKeyword){$score+=20;$reasons[]='Aadhaar identity indicator detected.';}else{$warnings[]='Aadhaar keyword was not clearly detected.';}

    $gov=false;
    foreach(['GOVERNMENT OF INDIA','GOVT OF INDIA','GOVERNMENT','GOVT.','INDIA','भारत सरकार','भारत'] as $w){if(stripos($upper,strtoupper($w))!==false||strpos($text,$w)!==false){$gov=true;break;}}
    if($gov){$score+=10;$reasons[]='Government/India indicator detected.';}else{$warnings[]='Government/India indicator not clearly detected.';}

    $dob=false;
    foreach(['/\b[0-3]?[0-9][\/\-][0-1]?[0-9][\/\-][12][0-9]{3}\b/','/\b[12][0-9]{3}[\/\-][0-1]?[0-9][\/\-][0-3]?[0-9]\b/','/\bDOB\b/i','/\bDATE OF BIRTH\b/i','/\bYEAR OF BIRTH\b/i'] as $p){if(preg_match($p,$text)){$dob=true;break;}}
    if($dob){$score+=8;$reasons[]='Date-of-birth information detected.';}else{$warnings[]='Date-of-birth information not detected.';}

    $gender=false;
    foreach(['MALE','FEMALE','TRANSGENDER','पुरुष','महिला'] as $w){if(stripos($upper,strtoupper($w))!==false||strpos($text,$w)!==false){$gender=true;break;}}
    if($gender){$score+=5;$reasons[]='Demographic indicator detected.';}

    if(preg_match('/\b[1-9][0-9]{5}\b/',$text)){$score+=5;$reasons[]='Six-digit postal code pattern detected.';}

    $name='';
    foreach(preg_split('/\r\n|\r|\n/',$ocr) as $line){
        $line=trim($line);if($line==='')continue;$u=strtoupper($line);
        if(strpos($u,'GOVERNMENT')!==false||strpos($u,'GOVT')!==false||strpos($u,'INDIA')!==false||strpos($u,'AADHAAR')!==false||strpos($u,'AADHAR')!==false||strpos($u,'DATE OF BIRTH')!==false||strpos($u,'DOB')!==false||strpos($u,'YEAR OF BIRTH')!==false||strpos($u,'MALE')!==false||strpos($u,'FEMALE')!==false||strpos($line,'भारत')!==false||strpos($line,'आधार')!==false)continue;
        if(preg_match('/[0-9]/',$line))continue;
        $clean=trim(preg_replace('/\s+/',' ',preg_replace('/[^A-Za-zÀ-ÿ .\'\-]/','',$line)));
        if(strlen($clean)>=3&&strlen($clean)<=60&&preg_match('/[A-Za-z]{2,}/',$clean)){$name=$clean;break;}
    }
    if($name===''&&$jsName!==''){$clean=trim(preg_replace('/\s+/',' ',preg_replace('/[^A-Za-zÀ-ÿ .\'\-]/','',$jsName)));if(strlen($clean)>=3&&strlen($clean)<=60)$name=$clean;}
    if($name!==''){$score+=7;$reasons[]='Possible document-holder name detected.';}else{$warnings[]='Holder name could not be confidently detected.';}

    $length=strlen(trim($ocr));
    if($length>=150){$score+=10;$reasons[]='OCR extracted sufficient document information.';}elseif($length>=80){$score+=6;$reasons[]='OCR extracted moderate document information.';}elseif($length>=40){$score+=2;$warnings[]='OCR output is limited.';}else{$warnings[]='OCR output is too short.';}

    $susp=[];
    foreach(['DUPLICATE','SAMPLE','SPECIMEN','FAKE','DEMO','NOT VALID','INVALID','FOR DEMO','SAMPLE COPY'] as $w){if(stripos($upper,$w)!==false)$susp[]=$w;}
    if($susp){$score-=55;$warnings[]='Suspicious document indicator detected: '.implode(', ',$susp);}
    $score=max(0,min(100,$score));

    $hasNum=$aadhaar!=='';$hasName=$name!=='';
    if($susp)$status='REJECTED';
    elseif($hasNum&&$gov&&$hasName){$status='APPROVED';$reasons[]='Strong document identity pattern detected.';}
    elseif($aadhaarKeyword&&$gov&&$dob&&$hasName){$status='APPROVED';$reasons[]='Aadhaar identity, government and demographic information matched.';}
    elseif($aadhaarKeyword&&$gov&&$gender&&$hasName&&$length>=80){$status='APPROVED';$reasons[]='Aadhaar identity and supporting demographic information detected.';}
    elseif($gov&&$dob&&$gender&&$hasName&&$length>=100){$status='APPROVED';$reasons[]='Multiple supporting identity fields detected despite OCR number limitations.';}
    else{$status='REJECTED';$reasons[]='Required Aadhaar identity evidence was insufficient for approval.';}
    if($status==='REJECTED')$warnings[]='Document did not meet the minimum DigiVerify screening criteria.';

    $masked='XXXX XXXX XXXX';
    if($aadhaar){$p=explode(' ',$aadhaar);if(count($p)===3)$masked='XXXX XXXX '.$p[2];}
    if($qrStatus!=='DETECTED')$qrStatus='NOT_DETECTED';
    $kycStatus='NOT_CONFIGURED';
    $transaction='NOT_AVAILABLE';
    if($qrStatus==='DETECTED')$reasons[]='QR code detected in the uploaded document image.';else$warnings[]='Secure QR code was not detected by browser QR scanner.';

    $params=['status'=>$status,'score'=>$score,'name'=>$name,'aadhaar'=>$masked,'qr_status'=>$qrStatus,'qr_data'=>$qrData,'kyc_status'=>$kycStatus,'transaction_id'=>$transaction,'reason'=>implode('|',$reasons),'warning'=>implode('|',$warnings)];
    header('Location: verification_result.php?'.http_build_query($params));exit;
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
