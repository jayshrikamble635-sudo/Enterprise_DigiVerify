<?php
session_start();

// 1. Database Connection (Yahan apni db details dalein)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Apne sahi database ka naam likhein

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Form Data Receive karna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_role = $_POST['user_role'];
    $user_id = $_SESSION['user_id'] ?? null; // Agar session me user id hai to
    $document_type = $_POST['document_type'];
    $document_name = $_POST['document_name'];
    $accuracy_score = $_POST['accuracy_score'];
    $status = $_POST['status']; // 'Approved' ya 'Rejected'

    // 3. Database me Data Insert karna
    $stmt = $conn->prepare("INSERT INTO verification_records (user_role, user_id, document_type, document_name, accuracy_score, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $user_role, $user_id, $document_type, $document_name, $accuracy_score, $status);

    if ($stmt->execute()) {
        // Record save hone ke baad user ko success message ke sath home par bhejein
        echo "<script>alert('Record Saved Successfully as " . $status . "!'); window.location.href='/Enterprise_DigiVerify/';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
