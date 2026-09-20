<?php
include("../database/config.php");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="documents.csv"');

$output = fopen("php://output", "w");

fputcsv($output, array(
    "ID",
    "Email",
    "Document",
    "Quality",
    "Fraud Score",
    "AI Score",
    "Result",
    "Recommendation",
    "Status",
    "Date"
));

$result = mysqli_query($conn,"SELECT * FROM documents ORDER BY id DESC");

while($row=mysqli_fetch_assoc($result)){

    fputcsv($output,array(
        $row['id'],
        $row['email'],
        $row['document_type'],
        $row['quality'],
        $row['fraud_score'],
        $row['ai_confidence'],
        $row['result'],
        $row['recommendation'],
        $row['status'],
        $row['uploaded_at']
    ));

}

fclose($output);
exit;
?>