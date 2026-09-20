<?php

include "config/api_config.php";
include "config/database.php";

if (!isset($_FILES["document"])) {
    die("No File Uploaded.");
}

$file = $_FILES["document"]["tmp_name"];
$fileType = $_FILES["document"]["type"];
$fileName = $_FILES["document"]["name"];

// Allowed File Types
$allowed = [
    "image/jpeg",
    "image/jpg",
    "image/png",
    "application/pdf"
];

if (!in_array($fileType, $allowed)) {
    die("Invalid File Type.");
}

// OCR API Request

$curl = curl_init();

curl_setopt_array($curl, [

    CURLOPT_URL => "https://api.ocr.space/parse/image",

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [
        "apikey: " . OCR_API_KEY
    ],

    CURLOPT_POSTFIELDS => [

        "language" => "eng",

        "OCREngine" => "2",

        "isOverlayRequired" => "false",

        "detectOrientation" => "true",

        "scale" => "true",

        "file" => new CURLFile(
            $file,
            $fileType,
            $fileName
        )

    ]

]);

$response = curl_exec($curl);

if (curl_errno($curl)) {

    die("cURL Error : " . curl_error($curl));

}

curl_close($curl);

// Decode JSON

$result = json_decode($response, true);

// OCR Error

if (
    isset($result["IsErroredOnProcessing"]) &&
    $result["IsErroredOnProcessing"] == true
) {

    die("OCR Error : " . $result["ErrorMessage"][0]);

}

// Extract OCR Text

$text = "";

if (isset($result["ParsedResults"][0]["ParsedText"])) {

    $text = trim($result["ParsedResults"][0]["ParsedText"]);

}

if ($text == "") {

    die("No text detected in document.");

}

// =========================
// PART 2 starts below
// =========================
// =========================
// Document Detection & Verification
// =========================

$status = "Rejected";
$reason = "Invalid or Unsupported Document";
$documentType = "Unknown";
$score = 0;

// -------------------------
// Aadhaar Card
// -------------------------

if (
    stripos($text, "Government of India") !== false ||
    stripos($text, "Unique Identification Authority of India") !== false ||
    stripos($text, "UIDAI") !== false
) {

    $documentType = "Aadhaar";

    if (preg_match('/\b\d{4}\s?\d{4}\s?\d{4}\b/', $text)) {
        $score += 2;
    }

    if (
        stripos($text, "MALE") !== false ||
        stripos($text, "FEMALE") !== false
    ) {
        $score++;
    }

    if (
        stripos($text, "DOB") !== false ||
        stripos($text, "Year of Birth") !== false ||
        stripos($text, "YOB") !== false ||
        preg_match('/\d{2}\/\d{2}\/\d{4}/', $text)
    ) {
        $score++;
    }

    if (preg_match('/[A-Z][a-z]+\s+[A-Z][a-z]+/', $text)) {
        $score++;
    }
}

// -------------------------
// PAN Card
// -------------------------

if (
    stripos($text, "INCOME TAX") !== false ||
    stripos($text, "Permanent Account Number") !== false
) {

    $documentType = "PAN";

    if (preg_match('/[A-Z]{5}[0-9]{4}[A-Z]/', $text)) {
        $score = 5;
    }
}

// -------------------------
// Passport
// -------------------------

if (
    stripos($text, "Passport") !== false ||
    stripos($text, "Republic of India") !== false
) {

    $documentType = "Passport";

    if (preg_match('/[A-PR-WYa-pr-wy][0-9]{7}/', $text)) {
        $score = 5;
    }
}

// -------------------------
// Voter ID
// -------------------------

if (
    stripos($text, "Election Commission of India") !== false ||
    stripos($text, "Elector Photo Identity Card") !== false
) {

    $documentType = "Voter ID";

    if (preg_match('/[A-Z]{3}[0-9]{7}/', $text)) {
        $score = 5;
    }
}

// -------------------------
// Driving Licence
// -------------------------

if (
    stripos($text, "Driving Licence") !== false ||
    stripos($text, "Driving License") !== false
) {

    $documentType = "Driving Licence";

    if (preg_match('/[A-Z]{2}[0-9]{2}\s?[0-9]{11}/', $text)) {
        $score = 5;
    }
}

// =========================
// PART 3 starts below
// =========================
// =========================
// Final Decision
// =========================

if ($score >= 4) {

    $status = "Approved";
    $reason = "Document Verified Successfully";

}
elseif ($score >= 2) {

    $status = "Pending";
    $reason = "Needs Manual Review";

}
else {

    $status = "Rejected";
    $reason = "Invalid or Incomplete Document";

}

// Confidence

$confidence = $score * 20;

if ($confidence > 100) {
    $confidence = 100;
}
$fullname = $_POST["fullname"] ?? "";
$email = $_POST["email"] ?? "";

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO verification_history
    (fullname,email,document_type,status,reason,confidence,ocr_text)
    VALUES (?,?,?,?,?,?,?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssssis",
    $fullname,
    $email,
    $documentType,
    $status,
    $reason,
    $confidence,
    $text
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>DigiVerify Result</title>

<style>

body{

font-family:Arial;
background:#eef3ff;
margin:0;
padding:40px;

}

.container{

width:700px;
margin:auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 15px rgba(0,0,0,.15);

}

h1{

text-align:center;
color:#0d6efd;

}

.approved{

color:green;
font-size:28px;

}

.pending{

color:orange;
font-size:28px;

}

.rejected{

color:red;
font-size:28px;

}

.info{

font-size:20px;
margin:10px 0;

}

pre{

background:#f4f4f4;
padding:15px;
border-radius:8px;
white-space:pre-wrap;

}

button{

padding:12px 25px;
background:#0d6efd;
color:white;
border:none;
border-radius:5px;
cursor:pointer;

}

</style>

</head>

<body>

<div class="container">

<h1>DigiVerify</h1>

<h2>Document Verification Result</h2>

<?php

if($status=="Approved"){

echo "<div class='approved'>✅ APPROVED</div>";

}
elseif($status=="Pending"){

echo "<div class='pending'>🟡 PENDING</div>";

}
else{

echo "<div class='rejected'>❌ REJECTED</div>";

}

?>

<div class="info">

<b>Document :</b>

<?php echo htmlspecialchars($documentType); ?>

</div>

<div class="info">

<b>Reason :</b>

<?php echo htmlspecialchars($reason); ?>

</div>

<div class="info">

<b>Confidence :</b>

<?php echo $confidence; ?>%

</div>

<hr>

<h3>OCR Extracted Text</h3>

<pre><?php echo htmlspecialchars($text); ?></pre>

<br>

<a href="user/upload.php">

<button>Upload Another Document</button>

</a>

</div>

</body>

</html>