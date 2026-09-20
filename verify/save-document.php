<?php
session_start();
include("../database/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die("Invalid Request");
}

$user_id = 1; // Demo User

$email = trim($_POST['email'] ?? '');
$document_type = $_POST['document_type'] ?? '';
$file_name = $_POST['file_name'] ?? '';
$file_path = $_POST['file_path'] ?? '';

$ocr_text = $_POST['ocr_text'] ?? 'Demo Verification';

$confidence = $_POST['confidence'] ?? 95;

$status = $_POST['status'] ?? 'Approved';

$recommendation = $_POST['recommendation'] ?? 'Original Document';

$stmt = $conn->prepare("
INSERT INTO documents
(
    user_id,
    email,
    document_type,
    file_name,
    file_path,
    ocr_text,
    confidence,
    status,
    recommendation,
    uploaded_at
)
VALUES
(
    ?,?,?,?,?,?,?,?, ?,NOW()
)
");

if (!$stmt) {
    die("Prepare Failed : " . $conn->error);
}

$stmt->bind_param(
    "isssssiss",
    $user_id,
    $email,
    $document_type,
    $file_name,
    $file_path,
    $ocr_text,
    $confidence,
    $status,
    $recommendation
);

if ($stmt->execute()) {

    $_SESSION['success'] = "Document Saved Successfully.";

    header("Location: verification_result.php?id=" . $stmt->insert_id);
    exit();

} else {

    die("Database Error : " . $stmt->error);

}

$stmt->close();
$conn->close();
?>