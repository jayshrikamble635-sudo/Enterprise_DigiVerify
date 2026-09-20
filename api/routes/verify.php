<?php

header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Only POST method allowed"
    ]);
    exit;
}

$document_number = trim($_POST['document_number'] ?? '');
$document_type = trim($_POST['document_type'] ?? '');

if (empty($document_number) || empty($document_type)) {
    echo json_encode([
        "status" => false,
        "message" => "Document number and type are required"
    ]);
    exit;
}

/* Mock Verification */

$sampleData = [
    "aadhaar" => [
        "123412341234",
        "987654321098"
    ],
    "pan" => [
        "ABCDE1234F",
        "PQRSX6789K"
    ]
];

$isVerified = false;

if (isset($sampleData[$document_type])) {
    if (in_array($document_number, $sampleData[$document_type])) {
        $isVerified = true;
    }
}

echo json_encode([
    "status" => true,
    "document_type" => $document_type,
    "document_number" => $document_number,
    "verification_status" => $isVerified ? "Verified" : "Rejected"
]);