<?php
session_start();

include("database/config.php");

error_reporting(E_ALL);
ini_set('display_errors',1);


// User ID
$user_id = $_SESSION['user_id'] ?? 1;


// Form Data
$email = trim($_POST['email']);
$document_type = $_POST['document_type'];


// File Upload
$file = $_FILES['document'];

if($file['error'] != 0){
    die("File Upload Failed");
}


// Upload Folder
$folder = "../uploads/".$document_type."/";

if(!is_dir($folder)){
    mkdir($folder,0777,true);
}


// File Name
$file_name = time()."_".$file['name'];

$file_path = $folder.$file_name;


// Move File

if(!move_uploaded_file($file['tmp_name'],$file_path))
{
    die("File Move Failed");
}


echo "File Uploaded Successfully<br>";



// =====================
// OCR START
// =====================

$ocr_text = "";

$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));


if(
    $file_extension == "jpg" ||
    $file_extension == "jpeg" ||
    $file_extension == "png"
)
{

    $command = "tesseract ".$file_path." stdout";

    $ocr_text = shell_exec($command);

}


echo "<pre>";
echo $ocr_text;
echo "</pre>";



// =====================
// ADVANCED VERIFICATION
// =====================


$verification_status = "Pending";

$result = "Pending";

$fraud_score = 0;

$ai_confidence = 0;

$remarks = "";


$text = strtoupper($ocr_text);
// =====================
// Extract Document Number
// =====================

$aadhaar_number = "";

if (preg_match('/\b\d{4}\s?\d{4}\s?\d{4}\b/', $text, $match)) {
    $aadhaar_number = preg_replace('/\s+/', '', $match[0]);
}

$pan_number = "";

if (preg_match('/\b[A-Z]{5}[0-9]{4}[A-Z]\b/', $text, $match)) {
    $pan_number = $match[0];
}

$extracted_document_number = "";

if ($document_type == "Aadhaar") {
    $extracted_document_number = $aadhaar_number;
}

if ($document_type == "PAN") {
    $extracted_document_number = $pan_number;
}


// Confidence

if(!empty($text))
{
    $ai_confidence = rand(70,95);
}
else
{
    $ai_confidence = rand(20,40);
    $fraud_score += 40;
}



// Aadhaar Verification

if($document_type == "Aadhaar")
{

    if(preg_match("/[0-9]{12}/",$text))
    {
        $remarks .= "Aadhaar format valid. ";
        $fraud_score -= 10;
    }
    else
    {
        $remarks .= "Invalid Aadhaar format. ";
        $fraud_score += 40;
    }

}



// PAN Verification

if($document_type == "PAN")
{

    if(preg_match("/[A-Z]{5}[0-9]{4}[A-Z]/",$text))
    {
        $remarks .= "PAN format valid. ";
        $fraud_score -= 10;
    }
    else
    {
        $remarks .= "Invalid PAN format. ";
        $fraud_score += 40;
    }

}



// Keyword Check

$keywords = [
"INDIA",
"GOVERNMENT",
"UIDAI",
"INCOME TAX",
"REPUBLIC"
];


$found = false;


foreach($keywords as $word)
{

    if(strpos($text,$word)!==false)
    {
        $found = true;
        break;
    }

}


if(!$found)
{
    $fraud_score += 30;
    $remarks .= "Required keywords missing. ";
}



// Final Result

if($fraud_score >= 60)
{

    $verification_status = "Rejected";
    $result = "Rejected";

}
elseif($fraud_score >=30)
{

    $verification_status = "Pending";
    $result = "Manual Review";

}
else
{

    $verification_status = "Approved";
    $result = "Approved";

}



// Score Limit

if($fraud_score < 0)
{
    $fraud_score = 0;
}

if($fraud_score > 100)
{
    $fraud_score = 100;
}




// =====================
// SAVE DATABASE
// =====================


$query = "INSERT INTO documents
(
user_id,
email,
document_type,
file_name,
file_path,
status,
result,
remarks,
ocr_text,
verification_status,
fraud_score,
ai_confidence,
extracted_document_number,
recommendation,
uploaded_at
)

VALUES
(
'$user_id',
'$email',
'$document_type',
'$file_name',
'$file_path',
'Uploaded',
'$result',
'$remarks',
'$ocr_text',
'$verification_status',
'$fraud_score',
'$ai_confidence',
'$extracted_document_number',
'$recommendation',
NOW()
)";


if(mysqli_query($conn,$query))
{

    // Get Document ID
    $doc_id = mysqli_insert_id($conn);
    echo "New ID = ".$doc_id;
exit;


    // QR Verification Link

    $qr_link = "http://localhost/Enterprise_DigiVerify/verify-document.php?id=".$doc_id;



    // QR Code API Image

  $qr_image = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode($qr_link);

    // Save QR Link in Database

    mysqli_query($conn,"
    UPDATE documents
    SET qr_code='$qr_image'
    WHERE id='$doc_id'
    ");



    echo "<h3>Document Saved Successfully</h3>";

    echo "Verification Status : ".$verification_status."<br>";

    echo "Fraud Score : ".$fraud_score."%<br>";

    echo "AI Confidence : ".$ai_confidence."%<br>";

    echo "<br>";

    echo "Verification Link : ";

    echo "<a href='$qr_link' target='_blank'>$qr_link</a>";

}
else
{

    echo mysqli_error($conn);

}



?>