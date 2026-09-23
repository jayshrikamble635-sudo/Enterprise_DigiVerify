<?php
error_reporting(0);
ini_set('display_errors', '0');

require_once __DIR__ . '/../database/config.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die('ERROR: Database connection failed.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die('ERROR: Invalid document ID.');

$sql = "SELECT d.*, u.name AS user_name, u.email AS user_email
        FROM documents d
        LEFT JOIN users u ON d.user_id = u.id
        WHERE d.id = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) die('ERROR: Unable to prepare database query.');

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    die('ERROR: Verification record not found.');
}

$doc = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

function e($v) {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

$status = strtolower(trim((string)($doc['status'] ?? 'Pending')));
$type = trim((string)($doc['document_type'] ?? $doc['type'] ?? ''));
$name = trim((string)($doc['document_name'] ?? $doc['file_name'] ?? ''));
$number = trim((string)($doc['document_number'] ?? $doc['document_no'] ?? ''));
$confidence = $doc['ai_confidence'] ?? null;
$fraud = $doc['fraud_score'] ?? null;
$qr = (int)($doc['qr_verified'] ?? 0);
$face = $doc['face_match'] ?? ($doc['face_match_score'] ?? null);

$isAadhaar = stripos($type, 'aadhaar') !== false || stripos($type, 'aadhar') !== false;

if ($isAadhaar && $qr === 1) {
    $label = 'AADHAAR VERIFIED'; $class = 'verified';
    $message = 'Secure QR verification is marked as successful.';
} elseif ($status === 'approved' && !$isAadhaar) {
    $label = 'DOCUMENT APPROVED'; $class = 'verified';
    $message = 'The document record is marked as approved.';
} elseif ($status === 'rejected') {
    $label = 'NOT VERIFIED'; $class = 'rejected';
    $message = 'The document record is marked as rejected.';
} else {
    $label = 'NOT VERIFIED'; $class = 'pending';
    $message = 'The document has not been cryptographically verified.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DigiVerify - Verification Result</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#06111f;color:#eaf6ff}
.wrap{max-width:1050px;margin:40px auto;padding:20px}.card{background:linear-gradient(145deg,#0b1b30,#081525);border:1px solid #164766;border-radius:18px;box-shadow:0 18px 55px #0008;overflow:hidden}
.header{padding:28px 30px;border-bottom:1px solid #164766}.brand{color:#35d9ff;font-size:13px;font-weight:bold;letter-spacing:2px;text-transform:uppercase}
h1{margin:8px 0 0;font-size:30px}.result{margin:25px 30px;padding:24px;border-radius:14px;border:1px solid #244d65}
.result.verified{border-color:#16c784}.result.rejected{border-color:#ff4d67}.result.pending{border-color:#f0b429}
.badge{display:inline-block;padding:9px 15px;border-radius:999px;font-weight:800;letter-spacing:.7px}
.verified .badge{background:#073d2c;color:#47efb0}.rejected .badge{background:#4a1320;color:#ff7f93}.pending .badge{background:#463510;color:#ffd36a}
.message{margin:15px 0 0;color:#a9c3d4}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px;padding:0 30px 30px}
.item{background:#0a1727;border:1px solid #17364b;border-radius:12px;padding:17px}.label{color:#6f9bb2;font-size:12px;text-transform:uppercase;letter-spacing:1px}
.value{margin-top:7px;font-size:16px;word-break:break-word}.footer{padding:18px 30px;border-top:1px solid #164766;color:#6f9bb2;font-size:12px}
@media(max-width:700px){.grid{grid-template-columns:1fr}.wrap{margin:10px auto;padding:10px}.header,.footer{padding-left:20px;padding-right:20px}.result{margin-left:20px;margin-right:20px}.grid{padding-left:20px;padding-right:20px}}
</style>
</head>
<body><div class="wrap"><div class="card">
<div class="header"><div class="brand">Enterprise DigiVerify</div><h1>Document Verification Result</h1></div>
<div class="result <?php echo e($class); ?>"><span class="badge"><?php echo e($label); ?></span><div class="message"><?php echo e($message); ?></div></div>
<div class="grid">
<div class="item"><div class="label">Verification ID</div><div class="value">DV-<?php echo e(str_pad((string)$id,6,'0',STR_PAD_LEFT)); ?></div></div>
<div class="item"><div class="label">Status</div><div class="value"><?php echo e($doc['status'] ?? 'Pending'); ?></div></div>
<div class="item"><div class="label">Document Type</div><div class="value"><?php echo e($type ?: 'Not available'); ?></div></div>
<div class="item"><div class="label">Document Name</div><div class="value"><?php echo e($name ?: 'Not available'); ?></div></div>
<div class="item"><div class="label">Document Number</div><div class="value"><?php echo e($number ?: 'Not available'); ?></div></div>
<div class="item"><div class="label">AI Confidence</div><div class="value"><?php echo $confidence!==null&&$confidence!==''?e($confidence).'%':'Not available'; ?></div></div>
<div class="item"><div class="label">Fraud Score</div><div class="value"><?php echo $fraud!==null&&$fraud!==''?e($fraud):'Not available'; ?></div></div>
<div class="item"><div class="label">Secure QR Verification</div><div class="value"><?php echo $isAadhaar?($qr===1?'VERIFIED':'NOT VERIFIED'):($qr===1?'VERIFIED':'Not verified'); ?></div></div>
<div class="item"><div class="label">Face Match</div><div class="value"><?php echo $face!==null&&$face!==''?e($face):'Not available'; ?></div></div>
<div class="item"><div class="label">User</div><div class="value"><?php echo e($doc['user_name'] ?? 'Not available'); ?></div></div>
<div class="item"><div class="label">Email</div><div class="value"><?php echo e($doc['user_email'] ?? 'Not available'); ?></div></div>
<div class="item"><div class="label">Database Record ID</div><div class="value"><?php echo e($id); ?></div></div>
</div>
<div class="footer">Enterprise DigiVerify • Verification result generated from the stored database record.</div>
</div></div></body></html>
