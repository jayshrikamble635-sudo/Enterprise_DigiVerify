<?php

require_once __DIR__ . '/../database/config.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("ERROR: Database connection not available.");
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid verification ID.");
}

$stmt = $conn->prepare("
    SELECT
        id,
        document_type,
        document_number,
        status,
        qr_verified,
        ai_confidence,
        fraud_score
    FROM documents
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$doc = $result->fetch_assoc();

$stmt->close();

if (!$doc) {
    die("Verification record not found.");
}

/*
|--------------------------------------------------------------------------
| FINAL VERIFICATION RULE
|--------------------------------------------------------------------------
|
| IMPORTANT:
| URL parameter cannot approve an Aadhaar.
| Filename cannot approve an Aadhaar.
| OCR cannot approve an Aadhaar.
|
| Only a successfully verified UIDAI Secure QR
| can produce VERIFIED.
|
*/

$qrVerified = (
    isset($doc['qr_verified']) &&
    (int)$doc['qr_verified'] === 1
);

$isAadhaar = (
    strtolower(trim($doc['document_type'] ?? '')) === 'aadhaar'
);

if ($isAadhaar && $qrVerified) {

    $finalStatus = 'APPROVED';
    $finalLabel = 'AADHAAR VERIFIED';
    $statusClass = 'verified';

} else {

    $finalStatus = 'REJECTED';
    $finalLabel = 'AADHAAR NOT VERIFIED';
    $statusClass = 'rejected';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Enterprise DigiVerify</title>

<style>

body {
    margin: 0;
    background: #06111f;
    color: #ffffff;
    font-family: Arial, sans-serif;
}

.container {
    max-width: 850px;
    margin: 60px auto;
    padding: 20px;
}

.card {
    background: #0b1b2d;
    border: 1px solid #19496a;
    border-radius: 18px;
    padding: 35px;
}

h1 {
    margin-top: 0;
}

.status {
    padding: 25px;
    border-radius: 14px;
    margin: 25px 0;
    text-align: center;
    font-size: 28px;
    font-weight: bold;
}

.verified {
    background: #063c2d;
    border: 2px solid #00d084;
    color: #00e69a;
}

.rejected {
    background: #42151b;
    border: 2px solid #ff4d5f;
    color: #ff6575;
}

.row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #1b344a;
    padding: 15px 0;
}

.label {
    color: #8da5ba;
}

.value {
    font-weight: bold;
}

.note {
    margin-top: 25px;
    padding: 18px;
    background: #071522;
    border-radius: 10px;
    color: #a9bdcd;
    line-height: 1.6;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h1>Enterprise DigiVerify</h1>

<div class="status <?= htmlspecialchars($statusClass) ?>">

<?= htmlspecialchars($finalLabel) ?>

</div>

<div class="row">
    <span class="label">Document Type</span>
    <span class="value">
        <?= htmlspecialchars($doc['document_type'] ?? 'N/A') ?>
    </span>
</div>

<div class="row">
    <span class="label">Document Number</span>
    <span class="value">
        <?= htmlspecialchars($doc['document_number'] ?? 'N/A') ?>
    </span>
</div>

<div class="row">
    <span class="label">UIDAI Secure QR</span>
    <span class="value">
        <?= $qrVerified ? 'VERIFIED' : 'NOT VERIFIED' ?>
    </span>
</div>

<div class="row">
    <span class="label">Final Status</span>
    <span class="value">
        <?= htmlspecialchars($finalStatus) ?>
    </span>
</div>

<div class="note">

<strong>Verification Method:</strong><br>

UIDAI Secure QR / Offline Digital Signature Verification.

<br><br>

The document is marked VERIFIED only when the
UIDAI Secure QR verification has successfully
validated the digitally signed data.

</div>

</div>

</div>

</body>

</html>
